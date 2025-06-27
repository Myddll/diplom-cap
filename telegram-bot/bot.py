import os
import logging
import requests
from datetime import datetime
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

# Конфигурация
TOKEN = os.getenv('TELEGRAM_TOKEN')
API_URL = os.getenv('API_URL', 'http://nginx/api')
SERVICES_API_URL = f"{API_URL}/getServices"
NEW_ORDER_API_URL = f"{API_URL}/newOrder"

# Состояния разговора
(
    SELECTING_SERVICE, SELECTING_TIME, GETTING_CONTACT,
    GETTING_LOCATION, CONFIRMING_ORDER, MANUAL_ADDRESS,
    GETTING_NAME, SELECTING_ADDITIONAL_SERVICES
) = range(8)

# Глобальные переменные
order_data = {}
services_data = {}
additional_services_selection = {}

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

async def load_services():
    """Загружает услуги из API и формирует структуру данных"""
    try:
        response = requests.get(SERVICES_API_URL)
        print(response)
        response.raise_for_status()
        print(response.raise_for_status())
        services = response.json()
        
        services_data['all'] = services
        services_data['primary'] = [s for s in services if s.get('is_primary_service')]
        services_data['additional'] = [s for s in services if not s.get('is_primary_service')]
        
        # Формируем клавиатуру для основных услуг
        keyboard = []
        row = []
        for service in services_data['primary']:
            row.append(f"{service['name']} ({service['price']})")
            if len(row) == 2:
                keyboard.append(row)
                row = []
        if row:
            keyboard.append(row)
        keyboard.append(["Отменить заказ"])
        
        return keyboard
    except Exception as e:
        logger.error(f"Ошибка при загрузке услуг: {e}")
        return [
            ["Генеральная уборка (2000 руб)", "Поддерживающая уборка (1500 руб)"],
            ["Уборка после ремонта (3000 руб)", "Мытьё окон (1000 руб)"],
            ["Отменить заказ"]
        ]

def create_additional_services_keyboard():
    """Создает клавиатуру для выбора дополнительных услуг"""
    keyboard = []
    for service in services_data['additional']:
        # Для услуг с is_multiple=True добавляем кнопки +/-
        if service.get('is_multiple'):
            btn_text = f"{service['name']} ({service['price']}) - 0"
            keyboard.append([
                InlineKeyboardButton("➖", callback_data=f"dec_{service['id']}"),
                InlineKeyboardButton(btn_text, callback_data=f"info_{service['id']}"),
                InlineKeyboardButton("➕", callback_data=f"inc_{service['id']}")
            ])
        else:
            # Для одиночных услуг - чекбокс
            btn_text = f"☑ {service['name']} ({service['price']})" if additional_services_selection.get(service['id']) else f"{service['name']} ({service['price']})"
            keyboard.append([InlineKeyboardButton(btn_text, callback_data=f"toggle_{service['id']}")])
    
    keyboard.append([
        InlineKeyboardButton("✅ Продолжить", callback_data="continue"),
        InlineKeyboardButton("❌ Отменить", callback_data="cancel")
    ])
    return InlineKeyboardMarkup(keyboard)



async def start(update: Update, context: ContextTypes.DEFAULT_TYPE):
    user = update.effective_user
    await update.message.reply_text(
        f"Привет, {user.first_name}! Я бот для заказа услуг уборки.\n"
        "Нажми /order чтобы сделать заказ или /help для справки."
    )

async def help_command(update: Update, context: ContextTypes.DEFAULT_TYPE):
    help_text = """
    Доступные команды:
    /start - начать общение
    /order - заказать уборку
    /help - показать это сообщение

    Просто нажмите /order и следуйте инструкциям!
    """
    await update.message.reply_text(help_text)

async def order(update: Update, context: ContextTypes.DEFAULT_TYPE):
    order_data.clear()
    additional_services_selection.clear()
    services_keyboard = await load_services()
    reply_markup = ReplyKeyboardMarkup(services_keyboard, one_time_keyboard=True, resize_keyboard=True)
    await update.message.reply_text(
        "Выберите основную услугу:",
        reply_markup=reply_markup
    )
    return SELECTING_SERVICE

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

# Отмена разговора
async def cancel(update: Update, context: ContextTypes.DEFAULT_TYPE):
    await update.message.reply_text(
        "Заказ отменён. Если передумаете, нажмите /order",
        reply_markup=ReplyKeyboardRemove()
    )
    return ConversationHandler.END

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

async def select_service(update: Update, context: ContextTypes.DEFAULT_TYPE):
    service_input = update.message.text
    if service_input == "Отменить заказ":
        return await cancel(update, context)

    # Ищем выбранную услугу (без учета цены в скобках)
    selected_service = None
    for service in services_data['primary']:
        if service['name'] in service_input:
            selected_service = service
            break

    if not selected_service:
        await update.message.reply_text("Произошла ошибка. Пожалуйста, попробуйте еще раз.")
        return await order(update, context)

    order_data['primary_service'] = {
        'id': selected_service['id'],
        'name': selected_service['name'],
        'price': selected_service['price'],
        'time': selected_service['time']
    }

    # Если есть дополнительные услуги, предлагаем их выбрать
    if services_data['additional']:
        await update.message.reply_text(
            "Выберите дополнительные услуги:",
            reply_markup=create_additional_services_keyboard()
        )
        return SELECTING_ADDITIONAL_SERVICES
    else:
        return await ask_for_time(update, context)

async def ask_for_time(update: Update, context: ContextTypes.DEFAULT_TYPE):
    reply_markup = ReplyKeyboardMarkup(time_keyboard, one_time_keyboard=True, resize_keyboard=True)
    await update.message.reply_text(
        "Выберите удобное время:",
        reply_markup=reply_markup
    )
    return SELECTING_TIME

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

async def handle_additional_services(update: Update, context: ContextTypes.DEFAULT_TYPE):
    query = update.callback_query
    await query.answer()

    if query.data == "continue":
        await query.edit_message_text("Дополнительные услуги выбраны!")
        return await ask_for_time_from_query(query, context)
    elif query.data == "cancel":
        await query.edit_message_text("Выбор услуг отменен.")
        return await cancel_from_query(query, context)
    elif query.data.startswith("toggle_"):
        service_id = int(query.data.split("_")[1])
        additional_services_selection[service_id] = not additional_services_selection.get(service_id, False)
    elif query.data.startswith("inc_"):
        service_id = int(query.data.split("_")[1])
        additional_services_selection[service_id] = additional_services_selection.get(service_id, 0) + 1
    elif query.data.startswith("dec_"):
        service_id = int(query.data.split("_")[1])
        current = additional_services_selection.get(service_id, 0)
        if current > 0:
            additional_services_selection[service_id] = current - 1

    await query.edit_message_reply_markup(reply_markup=create_additional_services_keyboard())
    return SELECTING_ADDITIONAL_SERVICES

async def ask_for_time_from_query(query, context):
    await query.message.reply_text(
        "Выберите удобное время:",
        reply_markup=ReplyKeyboardMarkup(time_keyboard, one_time_keyboard=True, resize_keyboard=True)
    )
    return SELECTING_TIME

async def select_time(update: Update, context: ContextTypes.DEFAULT_TYPE):
    time = update.message.text
    if time == "Отменить заказ":
        return await cancel(update, context)
    elif time == "Назад":
        services_keyboard = await load_services()
        reply_markup = ReplyKeyboardMarkup(services_keyboard, one_time_keyboard=True, resize_keyboard=True)
        await update.message.reply_text(
            "Выберите основную услугу:",
            reply_markup=reply_markup
        )
        return SELECTING_SERVICE

    order_data['selected_time'] = time
    await update.message.reply_text(
        "Пожалуйста, введите ваше ФИО:",
        reply_markup=ReplyKeyboardMarkup([["Отменить заказ"]], resize_keyboard=True)
    )
    return GETTING_NAME

async def get_name(update: Update, context: ContextTypes.DEFAULT_TYPE):
    if update.message.text == "Отменить заказ":
        return await cancel(update, context)

    order_data['client_name'] = update.message.text
    await update.message.reply_text(
        "Пожалуйста, поделитесь вашим контактом для связи:",
        reply_markup=contact_keyboard
    )
    return GETTING_CONTACT

async def send_order_to_api():
    """Формирует и отправляет заказ в API"""
    # Формируем список услуг
    services = []
    
    # Основная услуга
    if 'primary_service' in order_data:
        services.append({
            "id": order_data['primary_service']['id'],
            "quantity": 1
        })
    
    # Дополнительные услуги
    for service_id, quantity in additional_services_selection.items():
        if quantity > 0:
            services.append({
                "id": service_id,
                "quantity": quantity
            })
    
    # Преобразуем выбранное время в формат даты (примерная реализация)
    order_date = datetime.now().strftime("%Y-%m-%d") + " "
    if "Утро" in order_data.get('selected_time', ''):
        order_date += "09:00"
    elif "День" in order_data.get('selected_time', ''):
        order_date += "12:00"
    elif "Вечер" in order_data.get('selected_time', ''):
        order_date += "15:00"
    else:
        order_date += "10:00"  # По умолчанию
    
    payload = {
        "client_info": order_data.get('client_name', 'Не указано'),
        "client_tel": order_data.get('phone', 'Не указан'),
        "client_address": order_data.get('location', {}).get('address', 'Не указан'),
        "order_comment": f"Выбранное время: {order_data.get('selected_time', 'Не указано')}",
        "order_date": order_date,
        "services": services
    }
    
    try:
        response = requests.post(NEW_ORDER_API_URL, json=payload)
        response.raise_for_status()
        return response.json()
    except Exception as e:
        logger.error(f"Ошибка при отправке заказа: {e}")
        return None

async def confirm_order(update: Update, context: ContextTypes.DEFAULT_TYPE):
    choice = update.message.text
    if choice == "Отменить заказ":
        return await cancel(update, context)
    elif choice == "Изменить данные":
        services_keyboard = await load_services()
        reply_markup = ReplyKeyboardMarkup(services_keyboard, one_time_keyboard=True, resize_keyboard=True)
        await update.message.reply_text(
            "Выберите основную услугу:",
            reply_markup=reply_markup
        )
        return SELECTING_SERVICE
    elif choice == "Подтвердить заказ":
        api_response = await send_order_to_api()
        
        if api_response:
            order_number = api_response.get('order_id', 'Номер не получен')
            await update.message.reply_text(
                f"✅ Заказ #{order_number} успешно оформлен!\n"
                f"Спасибо за ваш выбор!\n\n"
                f"Детали заказа:\n"
                f"Услуга: {order_data['primary_service']['name']}\n"
                f"Время: {order_data['selected_time']}\n"
                f"Адрес: {order_data.get('location', {}).get('address', 'не указан')}\n\n"
                f"Наш менеджер свяжется с вами для подтверждения.",
                reply_markup=ReplyKeyboardRemove()
            )
        else:
            await update.message.reply_text(
                "❌ Произошла ошибка при оформлении заказа. Пожалуйста, попробуйте позже.",
                reply_markup=ReplyKeyboardRemove()
            )
        
        return ConversationHandler.END

def main():
    application = Application.builder().token(TOKEN).build()

    application.add_handler(CommandHandler("start", start))
    application.add_handler(CommandHandler("help", help_command))

    conv_handler = ConversationHandler(
        entry_points=[CommandHandler('order', order)],
        states={
            SELECTING_SERVICE: [MessageHandler(filters.TEXT & ~filters.COMMAND, select_service)],
            SELECTING_ADDITIONAL_SERVICES: [CallbackQueryHandler(handle_additional_services)],
            SELECTING_TIME: [MessageHandler(filters.TEXT & ~filters.COMMAND, select_time)],
            GETTING_NAME: [MessageHandler(filters.TEXT & ~filters.COMMAND, get_name)],
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
    application.run_polling()

if __name__ == "__main__":
    main()
