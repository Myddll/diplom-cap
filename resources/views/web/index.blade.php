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
                            <input type="number" id="service_{{ $service->id }}" min="1" max="500"
                                   data-price="{{ $service->price }}" data-time="{{ $service->time }}"
                                   class="quantity-input" value="1">
                        @else
                            <input type="checkbox" id="service_{{ $service->id }}"
                                   data-price="{{ $service->price }}" data-time="{{ $service->time }}">
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
                                <input type="number" name="extras[]" value="1" min="1" max="100"
                                       data-price="{{ $extra->price }}" data-time="{{ $extra->time }}"
                                       class="extra-quantity" style="width: 60px; margin-left: 10px;">
                                <span class="service-price">{{ $extra->price }} ₽/ед.</span>
                            @else
                                <input type="checkbox" name="extras[]" value="{{ $extra->id }}"
                                       data-price="{{ $extra->price }}" data-time="{{ $extra->time }}"/>
                                <span class="service-name">{{ $extra->name }}</span>
                                <span class="service-price">{{ $extra->price }} ₽</span>
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>

            <!-- Остальная часть формы без изменений -->
            <h2>Когда приехать?</h2>
            <div class="form-grid">
                <div>
                    <label for="date">Дата</label>
                    <input type="date" id="date" required min="{{ date('Y-m-d') }}">
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

        <div class="summary-card" id="summaryCard">
            <h3><i class="fas fa-receipt"></i> Ваш заказ</h3>
            <div id="orderSummary"></div>
        </div>
    </div>

    <script>
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

                // Валидация
                if (!document.getElementById('name').value ||
                    !document.getElementById('phone').value ||
                    !document.getElementById('date').value ||
                    !document.getElementById('time').value) {
                    alert('Пожалуйста, заполните все обязательные поля');
                    return;
                }

                // Сбор данных
                const formData = {
                    propertyType: document.getElementById('propertyType').value,
                    area: document.getElementById('area').value,
                    rooms: document.getElementById('rooms').value,
                    services: Array.from(document.querySelectorAll('#mainServices input[type="checkbox"]:checked')).map(el => el.value),
                    extras: Array.from(document.querySelectorAll('#extraServices input[type="checkbox"]:checked')).map(el => el.value),
                    date: document.getElementById('date').value,
                    time: document.getElementById('time').value,
                    name: document.getElementById('name').value,
                    phone: document.getElementById('phone').value,
                    comments: document.getElementById('comments').value,
                    total: document.getElementById('finalTotal').textContent,
                    timeNeeded: document.getElementById('totalTime').textContent
                };

                // Показ сводки
                showOrderSummary(formData);

                // Здесь можно добавить отправку данных на сервер через AJAX
                // axios.post('/order', formData)...
            });

            // Показать сводку заказа
            function showOrderSummary(data) {
                let summaryHTML = '';

                summaryHTML += `<div class="summary-item"><span>Тип помещения:</span> <span>${getPropertyTypeName(data.propertyType)}</span></div>`;
                summaryHTML += `<div class="summary-item"><span>Площадь:</span> <span>${data.area} м²</span></div>`;
                summaryHTML += `<div class="summary-item"><span>Комнат:</span> <span>${data.rooms}</span></div>`;

                if (data.services.length > 0) {
                    summaryHTML += `<div class="summary-item"><span>Основные услуги:</span> <span>${data.services.length}</span></div>`;
                }

                if (data.extras.length > 0) {
                    summaryHTML += `<div class="summary-item"><span>Доп. услуги:</span> <span>${data.extras.length}</span></div>`;
                }

                summaryHTML += `<div class="summary-item"><span>Дата и время:</span> <span>${formatDate(data.date)} в ${data.time}</span></div>`;
                summaryHTML += `<div class="summary-item"><span>Контактное лицо:</span> <span>${data.name}</span></div>`;
                summaryHTML += `<div class="summary-item"><span>Телефон:</span> <span>${data.phone}</span></div>`;

                if (data.comments) {
                    summaryHTML += `<div class="summary-item"><span>Комментарий:</span> <span>${data.comments}</span></div>`;
                }

                summaryHTML += `<div class="summary-item" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid var(--border);">
                    <span style="font-weight: 600;">Итого:</span> <span style="font-weight: 600; color: var(--primary);">${data.total}</span>
                </div>`;

                document.getElementById('orderSummary').innerHTML = summaryHTML;
                document.getElementById('summaryCard').style.display = 'block';

                // Прокрутка к сводке
                document.getElementById('summaryCard').scrollIntoView({ behavior: 'smooth' });

                // Изменение кнопки
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-check-circle"></i> Заказ оформлен!';
                document.getElementById('submitBtn').style.backgroundColor = 'var(--secondary)';
                document.getElementById('submitBtn').disabled = true;
            }

            // Вспомогательные функции
            function getPropertyTypeName(type) {
                const types = {
                    'apartment': 'Квартира',
                    'house': 'Дом',
                    'office': 'Офис',
                    'cottage': 'Коттедж'
                };
                return types[type] || type;
            }

            function formatDate(dateString) {
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                return new Date(dateString).toLocaleDateString('ru-RU', options);
            }
        });
    </script>
</body>
</html>
