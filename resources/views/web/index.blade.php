<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>CleanHouse - профессиональная уборка помещений</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --secondary: #10b981;
            --dark: #1f2937;
            --light: #f9fafb;
            --gray: #6b7280;
            --border: #e5e7eb;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            margin-bottom: 30px;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
        }
        .logo i {
            margin-right: 10px;
        }
        .form-container {
            background-color: #fff;
            width: 100%;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
        }
        h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
            color: var(--dark);
            font-weight: 700;
        }
        h2 {
            font-size: 20px;
            margin: 25px 0 15px;
            color: var(--dark);
            font-weight: 600;
        }
        label {
            font-weight: 500;
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
        }
        select, input, textarea {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: white;
            font-size: 16px;
            transition: all 0.3s;
        }
        select:focus, input:focus, textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .checkbox-group {
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .checkbox-item {
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 15px;
            transition: all 0.3s;
        }
        .checkbox-item:hover {
            border-color: var(--primary);
        }
        .checkbox-item label {
            display: flex;
            align-items: center;
            font-weight: 400;
            margin-bottom: 0;
            cursor: pointer;
        }
        .checkbox-item input[type="checkbox"] {
            margin-right: 12px;
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }
        .service-name {
            font-weight: 500;
        }
        .service-price {
            color: var(--primary);
            font-weight: 600;
            margin-left: auto;
        }
        .total-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
            border: 1px solid var(--border);
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-label {
            font-weight: 500;
        }
        .total-value {
            font-weight: 600;
        }
        .final-total {
            font-size: 20px;
            color: var(--primary);
            border-top: 1px solid var(--border);
            padding-top: 10px;
            margin-top: 10px;
        }
        button {
            background-color: var(--primary);
            color: white;
            padding: 16px;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        button:hover {
            background-color: var(--primary-hover);
        }
        button i {
            margin-right: 10px;
        }
        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-top: 30px;
            border: 1px solid var(--border);
            display: none;
        }
        .summary-card h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: var(--dark);
            font-weight: 600;
        }
        .summary-item {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        .footer {
            text-align: center;
            padding: 30px 0;
            color: var(--gray);
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .checkbox-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-broom"></i>
                CleanHouse
            </div>
            <div class="contacts">
                <a href="tel:+78001234567" style="color: var(--dark); text-decoration: none;">
                    <i class="fas fa-phone"></i> 8 (800) 123-45-67
                </a>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h1><i class="fas fa-broom"></i> Заказ уборки</h1>

        <form id="orderForm">
            <h2>Основные услуги</h2>

            <div class="form-grid" id="mainServices">
                @foreach(\App\Models\Service::query()->where('is_primary_service', '=', true)->get() as $service)
                    <h3>{{ $service->name }}</h3>
                    <div>
                        @if($service->is_multiple)
                            <label for="service_{{ $service->id }}">Количество</label>
                            <input type="number" id="service_{{ $service->id }}" min="0" max="500"
                                   data-price="{{ $service->price }}" data-time="{{ $service->time }}"
                                   class="quantity-input" value="0" data-service-id="{{ $service->id }}">
                        @else
                            <input type="checkbox" id="service_{{ $service->id }}"
                                   data-price="{{ $service->price }}" data-time="{{ $service->time }}"
                                   data-service-id="{{ $service->id }}">
                        @endif
                    </div>
                @endforeach
            </div>

            <h2>Дополнительные услуги</h2>
            <div class="checkbox-group" id="extraServices">
                @foreach(\App\Models\Service::query()->where('is_primary_service', '=', false)->get() as $extra)
                    <div class="checkbox-item">
                        <label>
                            @if($extra->is_multiple)
                                <span class="service-name">{{ $extra->name }}</span>
                                <input type="number" name="extras[]" value="0" min="0" max="100"
                                       data-price="{{ $extra->price }}" data-time="{{ $extra->time }}"
                                       class="extra-quantity" style="width: 60px; margin-left: 10px;"
                                       data-service-id="{{ $extra->id }}">
                                <span class="service-price">{{ $extra->price }} ₽/ед.</span>
                            @else
                                <input type="checkbox" name="extras[]" value="{{ $extra->id }}"
                                       data-price="{{ $extra->price }}" data-time="{{ $extra->time }}"
                                       data-service-id="{{ $extra->id }}"/>
                                <span class="service-name">{{ $extra->name }}</span>
                                <span class="service-price">{{ $extra->price }} ₽</span>
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>

            <h2>Когда приехать?</h2>
            <div class="form-grid">
                <div>
                    <label for="date">Дата</label>
                    <input type="date" id="date" required min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}">
                </div>
                <div>
                    <label for="time">Время</label>
                    <input type="time" id="time" required min="09:00" max="20:00">
                </div>
            </div>

            <h2>Контактные данные</h2>
            <div class="form-grid">
                <div>
                    <label for="name">Ваше имя</label>
                    <input type="text" id="name" placeholder="Иван Иванов" required>
                </div>
                <div>
                    <label for="phone">Телефон</label>
                    <input type="tel" id="phone" placeholder="+7 (___) ___-__-__" required>
                </div>
            </div>

            <label for="comments">Комментарий (необязательно)</label>
            <textarea id="comments" rows="3" placeholder="Особенности помещения, дополнительные пожелания..."></textarea>

            <div class="total-container">
                <div class="total-row">
                    <span class="total-label">Стоимость услуг:</span>
                    <span class="total-value" id="servicesTotal">0 ₽</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Дополнительные услуги:</span>
                    <span class="total-value" id="extrasTotal">0 ₽</span>
                </div>
                <div class="total-row final-total">
                    <span class="total-label">Итого:</span>
                    <span class="total-value" id="finalTotal">0 ₽</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Примерное время уборки:</span>
                    <span class="total-value" id="totalTime">—</span>
                </div>
            </div>

            <button type="submit" id="submitBtn">
                <i class="fas fa-check-circle"></i> Подтвердить заказ
            </button>
        </form>

        <div class="summary-card" id="summaryCard" style="display: none;">
            <h3><i class="fas fa-receipt"></i> Ваш заказ</h3>
            <div id="orderSummary"></div>
        </div>

        <div id="errorBlock" class="error-block" style="display: none; background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 5px; margin-top: 20px;">
            Ошибка валидации
        </div>
    </div>

    <script>
        function formatPhoneForApi(phone) {
            return '+7' + phone.replace(/\D/g, '').substring(1);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Маска для телефона
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
                e.target.value = !x[2] ? x[1] : '+7 (' + x[2] + (x[3] ? ') ' + x[3] : '') + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
            });

            // Расчет стоимости
            function calculateTotal() {
                let servicesTotal = 0;
                let extrasTotal = 0;
                let totalTime = 0;

                // Основные услуги
                document.querySelectorAll('#mainServices > div').forEach(serviceDiv => {
                    const checkbox = serviceDiv.querySelector('input[type="checkbox"]');
                    const quantityInput = serviceDiv.querySelector('input[type="number"]');

                    if (checkbox && checkbox.checked) {
                        const price = parseFloat(checkbox.dataset.price) || 0;
                        const time = parseInt(checkbox.dataset.time) || 0;
                        servicesTotal += price;
                        totalTime += time;
                    }

                    if (quantityInput) {
                        const quantity = parseInt(quantityInput.value) || 0;
                        const price = parseFloat(quantityInput.dataset.price) || 0;
                        const time = parseInt(quantityInput.dataset.time) || 0;
                        servicesTotal += price * quantity;
                        totalTime += time * quantity;
                    }
                });

                // Дополнительные услуги
                document.querySelectorAll('#extraServices .checkbox-item').forEach(extraItem => {
                    const checkbox = extraItem.querySelector('input[type="checkbox"]');
                    const quantityInput = extraItem.querySelector('input[type="number"]');

                    if (checkbox && checkbox.checked) {
                        const price = parseFloat(checkbox.dataset.price) || 0;
                        const time = parseInt(checkbox.dataset.time) || 0;
                        extrasTotal += price;
                        totalTime += time;
                    }

                    if (quantityInput) {
                        const quantity = parseInt(quantityInput.value) || 0;
                        const price = parseFloat(quantityInput.dataset.price) || 0;
                        const time = parseInt(quantityInput.dataset.time) || 0;
                        extrasTotal += price * quantity;
                        totalTime += time * quantity;
                    }
                });

                // Обновление UI
                document.getElementById('servicesTotal').textContent = Math.round(servicesTotal) + ' ₽';
                document.getElementById('extrasTotal').textContent = Math.round(extrasTotal) + ' ₽';
                document.getElementById('finalTotal').textContent = Math.round(servicesTotal + extrasTotal) + ' ₽';

                if (totalTime > 0) {
                    const hours = Math.floor(totalTime / 60);
                    const minutes = totalTime % 60;
                    document.getElementById('totalTime').textContent =
                        (hours > 0 ? hours + ' ч ' : '') + (minutes > 0 ? minutes + ' мин' : '');
                } else {
                    document.getElementById('totalTime').textContent = '—';
                }
            }

            // Слушатели изменений
            document.querySelectorAll('input[type="checkbox"], input[type="number"]').forEach(element => {
                element.addEventListener('change', calculateTotal);
                element.addEventListener('input', calculateTotal);
            });

            // Обработка отправки формы
            document.getElementById('orderForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Скрываем предыдущие сообщения
                document.getElementById('summaryCard').style.display = 'none';
                document.getElementById('errorBlock').style.display = 'none';

                // Валидация
                if (!document.getElementById('name').value ||
                    !document.getElementById('phone').value ||
                    !document.getElementById('date').value ||
                    !document.getElementById('time').value) {
                    showError('Пожалуйста, заполните все обязательные поля');
                    return;
                }

                // Сбор данных для API
                const formData = {
                    client_info: document.getElementById('name').value,
                    client_tel: formatPhoneForApi(document.getElementById('phone').value),
                    client_address: "ул. Пушкинна д. колотушкина кв. 1", // Здесь можно добавить поле адреса, если нужно
                    order_comment: document.getElementById('comments').value,
                    order_date: document.getElementById('date').value + ' ' + document.getElementById('time').value,
                    services: []
                };

                // Собираем основные услуги
                document.querySelectorAll('#mainServices input').forEach(input => {
                    const serviceId = input.dataset.serviceId;

                    if (input.type === 'checkbox' && input.checked) {
                        formData.services.push({
                            id: parseInt(serviceId),
                            quantity: 1
                        });
                    } else if (input.type === 'number' && parseInt(input.value) > 0) {
                        formData.services.push({
                            id: parseInt(serviceId),
                            quantity: parseInt(input.value)
                        });
                    }
                });

                // Собираем дополнительные услуги
                document.querySelectorAll('#extraServices input').forEach(input => {
                    const serviceId = input.dataset.serviceId;

                    if (input.type === 'checkbox' && input.checked) {
                        formData.services.push({
                            id: parseInt(serviceId),
                            quantity: 1
                        });
                    } else if (input.type === 'number' && parseInt(input.value) > 0) {
                        formData.services.push({
                            id: parseInt(serviceId),
                            quantity: parseInt(input.value)
                        });
                    }
                });

                // Проверка, что выбрана хотя бы одна услуга
                if (formData.services.length === 0) {
                    showError('Пожалуйста, выберите хотя бы одну услугу');
                    return;
                }

                // Отправка данных на сервер
                sendOrderData(formData);
            });

            // Функция отправки данных на сервер
            function sendOrderData(data) {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Отправка...';

                fetch('/api/newOrder', { // Замените на ваш API endpoint
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Успешный ответ
                        showOrderSummary(data);
                        submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Заказ оформлен!';
                        submitBtn.style.backgroundColor = 'var(--secondary)';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError(error.message || 'Ошибка валидации');
                        submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Подтвердить заказ';
                        submitBtn.disabled = false;
                    });
            }

            // Показать ошибку
            function showError(message) {
                const errorBlock = document.getElementById('errorBlock');
                errorBlock.textContent = message;
                errorBlock.style.display = 'block';
                errorBlock.scrollIntoView({ behavior: 'smooth' });
            }

            // Показать сводку заказа
            function showOrderSummary(isSuccess) {
                let summaryHTML = '';

                // Если заказ не создан, показываем ошибку
                if (!isSuccess) {
                    showError('Не удалось создать заказ');
                    return;
                }

                // Получаем данные из формы
                const name = document.getElementById('name').value;
                const phone = document.getElementById('phone').value;
                const comments = document.getElementById('comments').value;
                const orderDate = document.getElementById('date').value;
                const orderTime = document.getElementById('time').value;

                // Форматируем дату и время
                const formattedDate = new Date(orderDate).toLocaleDateString('ru-RU', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                // Основная информация
                summaryHTML += `<div class="summary-item"><span>Клиент:</span> <span>${name}</span></div>`;
                summaryHTML += `<div class="summary-item"><span>Телефон:</span> <span>${phone}</span></div>`;
                summaryHTML += `<div class="summary-item"><span>Дата и время:</span> <span>${formattedDate} в ${orderTime}</span></div>`;

                // Комментарий
                if (comments) {
                    summaryHTML += `<div class="summary-item"><span>Комментарий:</span> <span>${comments}</span></div>`;
                }

                // Собираем информацию об услугах
                const services = [];

                // Основные услуги
                document.querySelectorAll('#mainServices input').forEach(input => {
                    const serviceId = input.dataset.serviceId;
                    const serviceName = input.closest('div').previousElementSibling.textContent;

                    if (input.type === 'checkbox' && input.checked) {
                        services.push({
                            id: serviceId,
                            name: serviceName,
                            price: parseFloat(input.dataset.price) || 0,
                            quantity: 1
                        });
                    } else if (input.type === 'number' && parseInt(input.value) > 0) {
                        services.push({
                            id: serviceId,
                            name: serviceName,
                            price: parseFloat(input.dataset.price) || 0,
                            quantity: parseInt(input.value)
                        });
                    }
                });

                // Дополнительные услуги
                document.querySelectorAll('#extraServices input').forEach(input => {
                    const serviceId = input.dataset.serviceId;
                    const serviceName = input.closest('label').querySelector('.service-name').textContent;

                    if (input.type === 'checkbox' && input.checked) {
                        services.push({
                            id: serviceId,
                            name: serviceName,
                            price: parseFloat(input.dataset.price) || 0,
                            quantity: 1
                        });
                    } else if (input.type === 'number' && parseInt(input.value) > 0) {
                        services.push({
                            id: serviceId,
                            name: serviceName,
                            price: parseFloat(input.dataset.price) || 0,
                            quantity: parseInt(input.value)
                        });
                    }
                });

                // Выводим услуги
                if (services.length > 0) {
                    summaryHTML += `<div class="summary-item" style="margin-top: 10px;"><span style="font-weight: 600;">Услуги:</span></div>`;

                    services.forEach(service => {
                        summaryHTML += `<div class="summary-item" style="padding-left: 15px;">
                <span>${service.name}:</span>
                <span>${service.quantity} × ${service.price} ₽ = ${service.quantity * service.price} ₽</span>
            </div>`;
                    });

                    // Итоговая стоимость
                    const total = services.reduce((sum, service) => sum + (service.price * service.quantity), 0);
                    summaryHTML += `<div class="summary-item" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid var(--border);">
            <span style="font-weight: 600;">Итого:</span> <span style="font-weight: 600; color: var(--primary);">${total} ₽</span>
        </div>`;
                }

                document.getElementById('orderSummary').innerHTML = summaryHTML;
                document.getElementById('summaryCard').style.display = 'block';
                document.getElementById('summaryCard').scrollIntoView({ behavior: 'smooth' });
            }
        });
    </script>
</body>
</html>
