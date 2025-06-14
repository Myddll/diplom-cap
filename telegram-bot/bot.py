import os
import psycopg2
import logging
from telegram import (
    Update,
    ReplyKeyboardMarkup,
    ReplyKeyboardRemove,
    KeyboardButton,
    InlineKeyboardMarkup,
    InlineKeyboardButton
)
from telegram.ext import (
    Application,
    CommandHandler,
    MessageHandler,
    filters,
    ContextTypes,
    ConversationHandler,
    CallbackQueryHandler
)

# Настройка логгирования
logging.basicConfig(
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    level=logging.INFO
)
logger = logging.getLogger(__name__)

# Токен вашего бота
TOKEN = os.getenv('TELEGRAM_TOKEN')

# Состояния разговора
(
    SELECTING_SERVICE, SELECTING_TIME, GETTING_CONTACT,
    GETTING_LOCATION, CONFIRMING_ORDER, MANUAL_ADDRESS
) = range(6)

# Данные о заказе
order_data = {}

# Клавиатура с услугами
services_keyboard = [
    ["Генеральная уборка", "Поддерживающая уборка"],
    ["Уборка после ремонта", "Мытьё окон"],
    ["Отменить заказ"]
]

# Клавиатура с временем
time_keyboard = [
    ["Утро (9:00-12:00)", "День (12:00-15:00)"],
    ["Вечер (15:00-18:00)", "Любое время"],
    ["Назад", "Отменить заказ"]
]

# Клавиатура для контакта
contact_keyboard = ReplyKeyboardMarkup(
    [
        [KeyboardButton('Отправить контакт ☎️', request_contact=True)],
        ["Пропустить этот шаг"],
        ["Отменить заказ"]
    ],
    resize_keyboard=True
)

# Клавиатура для локации
location_keyboard = ReplyKeyboardMarkup(
    [
        [KeyboardButton('Отправить локацию 🗺️', request_location=True) ,"Ввести адрес вручную"],
        ["Пропустить этот шаг"],
        ["Отменить заказ"]
    ],
    resize_keyboard=True
)


# Инлайн клавиатура для подтверждения адреса
def create_address_confirmation_keyboard():
    return InlineKeyboardMarkup([
        [
            InlineKeyboardButton("✅ Подтвердить", callback_data="confirm_address"),
            InlineKeyboardButton("✏️ Изменить", callback_data="change_address")
        ]
    ])


# Клавиатура подтверждения
confirm_keyboard = [
    ["Подтвердить заказ", "Изменить данные"],
    ["Отменить заказ"]
]


# Обработчик команды /start
async def start(update: Update, context: ContextTypes.DEFAULT_TYPE):
    user = update.effective_user
    await update.message.reply_text(
        f"Привет, {user.first_name}! Я бот для заказа услуг уборки.\n"
        "Нажми /order чтобы сделать заказ или /help для справки."
    )


# Обработчик команды /help
async def help_command(update: Update, context: ContextTypes.DEFAULT_TYPE):
    help_text = """
    Доступные команды:
    /start - начать общение
    /order - заказать уборку
    /help - показать это сообщение

    Просто нажмите /order и следуйте инструкциям!
    """
    await update.message.reply_text(help_text)


# Начало заказа
async def order(update: Update, context: ContextTypes.DEFAULT_TYPE):
    reply_markup = ReplyKeyboardMarkup(services_keyboard, one_time_keyboard=True, resize_keyboard=True)
    await update.message.reply_text(
        "Выберите тип уборки:",
        reply_markup=reply_markup
    )
    return SELECTING_SERVICE


# Выбор услуги
async def select_service(update: Update, context: ContextTypes.DEFAULT_TYPE):
    service = update.message.text
    if service == "Отменить заказ":
        await update.message.reply_text(
            "Заказ отменён. Если хотите начать заново, нажмите /order",
            reply_markup=ReplyKeyboardRemove()
        )
        return ConversationHandler.END

    order_data['service'] = service
    reply_markup = ReplyKeyboardMarkup(time_keyboard, one_time_keyboard=True, resize_keyboard=True)
    await update.message.reply_text(
        "Выберите удобное время:",
        reply_markup=reply_markup
    )
    return SELECTING_TIME


# Выбор времени
async def select_time(update: Update, context: ContextTypes.DEFAULT_TYPE):
    time = update.message.text
    if time == "Отменить заказ":
        await update.message.reply_text(
            "Заказ отменён. Если хотите начать заново, нажмите /order",
            reply_markup=ReplyKeyboardRemove()
        )
        return ConversationHandler.END
    elif time == "Назад":
        reply_markup = ReplyKeyboardMarkup(services_keyboard, one_time_keyboard=True, resize_keyboard=True)
        await update.message.reply_text(
            "Выберите тип уборки:",
            reply_markup=reply_markup
        )
        return SELECTING_SERVICE

    order_data['time'] = time
    await update.message.reply_text(
        "Пожалуйста, поделитесь вашим контактом для связи:",
        reply_markup=contact_keyboard
    )
    return GETTING_CONTACT


# Получение контакта
async def get_contact(update: Update, context: ContextTypes.DEFAULT_TYPE):
    if update.message.contact:
        order_data['phone'] = update.message.contact.phone_number
        await update.message.reply_text(
            "Спасибо за контакт! Теперь укажите адрес уборки:",
            reply_markup=location_keyboard
        )
        return GETTING_LOCATION
    elif update.message.text == "Пропустить этот шаг":
        order_data['phone'] = "не указан"
        await update.message.reply_text(
            "Теперь укажите адрес уборки:",
            reply_markup=location_keyboard
        )
        return GETTING_LOCATION
    elif update.message.text == "Отменить заказ":
        return await cancel(update, context)
    else:
        await update.message.reply_text(
            "Пожалуйста, используйте кнопку для отправки контакта или пропустите этот шаг.",
            reply_markup=contact_keyboard
        )
        return GETTING_CONTACT


# Получение локации
async def get_location(update: Update, context: ContextTypes.DEFAULT_TYPE):
    if update.message.location:
        order_data['location'] = {
            'type': 'coordinates',
            'latitude': update.message.location.latitude,
            'longitude': update.message.location.longitude
        }
        await update.message.reply_text(
            "Локация получена! Спасибо.",
            reply_markup=ReplyKeyboardRemove()
        )
        return await confirm_order_step(update, context)
    elif update.message.text == "Ввести адрес вручную":
        await update.message.reply_text(
            "Пожалуйста, введите адрес уборки:",
            reply_markup=ReplyKeyboardMarkup([["Отменить заказ"]], resize_keyboard=True)
        )
        return MANUAL_ADDRESS
    elif update.message.text == "Пропустить этот шаг":
        order_data['location'] = {'type': 'не указан'}
        await update.message.reply_text(
            "Хорошо, адрес не указан.",
            reply_markup=ReplyKeyboardRemove()
        )
        return await confirm_order_step(update, context)
    elif update.message.text == "Отменить заказ":
        return await cancel(update, context)
    else:
        await update.message.reply_text(
            "Пожалуйста, используйте кнопки для указания адреса.",
            reply_markup=location_keyboard
        )
        return GETTING_LOCATION


# Обработка ручного ввода адреса
async def manual_address(update: Update, context: ContextTypes.DEFAULT_TYPE):
    if update.message.text == "Отменить заказ":
        return await cancel(update, context)

    address = update.message.text
    order_data['location'] = {
        'type': 'address',
        'address': address
    }

    # Сохраняем адрес в контексте для подтверждения
    context.user_data['manual_address'] = address

    await update.message.reply_text(
        f"Вы ввели адрес:\n{address}\n\nВсё верно?",
        reply_markup=create_address_confirmation_keyboard()
    )
    return CONFIRMING_ORDER


# Обработка инлайн кнопок подтверждения адреса
async def handle_address_confirmation(update: Update, context: ContextTypes.DEFAULT_TYPE):
    query = update.callback_query
    await query.answer()

    if query.data == "confirm_address":
        await query.edit_message_text("Адрес подтверждён!")
        return await confirm_order_step_from_query(query, context)
    elif query.data == "change_address":
        await query.edit_message_text("Пожалуйста, введите адрес уборки:")
        return MANUAL_ADDRESS


# Подготовка подтверждения заказа (из инлайн запроса)
async def confirm_order_step_from_query(query, context):
    await query.message.reply_text(
        "Проверьте детали заказа:",
        reply_markup=ReplyKeyboardMarkup(confirm_keyboard, resize_keyboard=True)
    )
    return await show_order_details(query.message, context)


# Подготовка подтверждения заказа
async def confirm_order_step(update: Update, context: ContextTypes.DEFAULT_TYPE):
    await update.message.reply_text(
        "Проверьте детали заказа:",
        reply_markup=ReplyKeyboardMarkup(confirm_keyboard, resize_keyboard=True)
    )
    return await show_order_details(update.message, context)


# Показать детали заказа
async def show_order_details(message, context):
    order_details = "Детали вашего заказа:\n\n"
    order_details += f"Услуга: {order_data.get('service', 'не указана')}\n"
    order_details += f"Время: {order_data.get('time', 'не указано')}\n"
    order_details += f"Телефон: {order_data.get('phone', 'не указан')}\n"

    location = order_data.get('location', {})
    if location.get('type') == 'coordinates':
        order_details += "Адрес: указан по геолокации\n"
        await message.reply_location(
            latitude=location['latitude'],
            longitude=location['longitude']
        )
    elif location.get('type') == 'address':
        order_details += f"Адрес: {location.get('address', 'не указан')}\n"
    else:
        order_details += "Адрес: не указан\n"

    order_details += "\nВсё верно?"

    await message.reply_text(order_details)
    return CONFIRMING_ORDER


# Подтверждение заказа
async def confirm_order(update: Update, context: ContextTypes.DEFAULT_TYPE):
    choice = update.message.text
    if choice == "Отменить заказ":
        return await cancel(update, context)
    elif choice == "Изменить данные":
        reply_markup = ReplyKeyboardMarkup(services_keyboard, one_time_keyboard=True, resize_keyboard=True)
        await update.message.reply_text(
            "Выберите тип уборки:",
            reply_markup=reply_markup
        )
        return SELECTING_SERVICE
    elif choice == "Подтвердить заказ":
        # Здесь можно добавить логику сохранения заказа в БД
        await update.message.reply_text(
            "Спасибо за заказ! Наш менеджер свяжется с вами в ближайшее время.\n"
            "Номер вашего заказа: #12345\n"
            "Если хотите сделать ещё один заказ, нажмите /order",
            reply_markup=ReplyKeyboardRemove()
        )
        return ConversationHandler.END


# Отмена разговора
async def cancel(update: Update, context: ContextTypes.DEFAULT_TYPE):
    await update.message.reply_text(
        "Заказ отменён. Если передумаете, нажмите /order",
        reply_markup=ReplyKeyboardRemove()
    )
    return ConversationHandler.END


def get_db_connection():
    return psycopg2.connect(
        host="postgres",  # имя сервиса в docker-compose
        database=os.getenv('POSTGRES_DB'),
        user=os.getenv('POSTGRES_USER'),
        password=os.getenv('POSTGRES_PASSWORD')
    )

def main():
    #подключаем бд
    try:
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT version();")
        print("PostgreSQL version:", cursor.fetchone())
    finally:
        if conn:
            conn.close()

    # Создаем приложение и передаем токен бота
    application = Application.builder().token(TOKEN).build()

    # Обработчик команд
    application.add_handler(CommandHandler("start", start))
    application.add_handler(CommandHandler("help", help_command))

    # Обработчик диалога заказа
    conv_handler = ConversationHandler(
        entry_points=[CommandHandler('order', order)],
        states={
            SELECTING_SERVICE: [MessageHandler(filters.TEXT & ~filters.COMMAND, select_service)],
            SELECTING_TIME: [MessageHandler(filters.TEXT & ~filters.COMMAND, select_time)],
            GETTING_CONTACT: [
                MessageHandler(filters.CONTACT, get_contact),
                MessageHandler(filters.TEXT & ~filters.COMMAND, get_contact)
            ],
            GETTING_LOCATION: [
                MessageHandler(filters.LOCATION, get_location),
                MessageHandler(filters.TEXT & ~filters.COMMAND, get_location)
            ],
            MANUAL_ADDRESS: [MessageHandler(filters.TEXT & ~filters.COMMAND, manual_address)],
            CONFIRMING_ORDER: [
                MessageHandler(filters.TEXT & ~filters.COMMAND, confirm_order),
                CallbackQueryHandler(handle_address_confirmation)
            ],
        },
        fallbacks=[CommandHandler('cancel', cancel)],
    )

    application.add_handler(conv_handler)

    # Запускаем бота
    application.run_polling()
#7561126039:Z5ztc28lD8AimtLJGx9I2wLpTm1


if __name__ == "__main__":
    main()
