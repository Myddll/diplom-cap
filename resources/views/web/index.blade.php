<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Заказ уборки</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; padding: 20px; margin: 0; display: flex; justify-content: center; }
        .form-container { background-color: #fff; max-width: 600px; width: 100%; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); }
        h1 { text-align: center; font-size: 24px; margin-bottom: 25px; color: #333; }
        label { font-weight: 600; display: block; margin-bottom: 5px; color: #444; }
        select, input { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 12px; background: #fafafa; font-size: 16px; transition: border-color 0.3s; }
        select:focus, input:focus { border-color: #6ab04c; outline: none; }
        .checkbox-group { margin-bottom: 20px; }
        .checkbox-group label { display: flex; align-items: center; font-weight: 400; margin-bottom: 10px; }
        .checkbox-group input[type="checkbox"] { margin-right: 10px; transform: scale(1.2); }
        .total { font-size: 18px; font-weight: 600; margin-top: 10px; color: #222; text-align: right; }
        button { background-color: #6ab04c; color: white; padding: 14px; width: 100%; font-size: 16px; border: none; border-radius: 12px; cursor: pointer; transition: background-color 0.3s; }
        button:hover { background-color: #4c8d36; }
        .services { margin-top: 40px; }
        .services h2 { font-size: 20px; margin-bottom: 15px; color: #333; }
        .services table { width: 100%; border-collapse: collapse; }
        .services th, .services td { border: 1px solid #e0e0e0; padding: 10px; text-align: left; }
        .services th { background-color: #f0f0f0; font-weight: 600; }
        .additional { margin-bottom: 20px; }
        .additional h3 { font-size: 18px; margin-bottom: 10px; color: #333; }
    </style>
</head>
<body>
<div class="form-container">
    <h1>Заказ уборки</h1>
    <form id="orderForm">
        <h2>Услуги</h2>
        @foreach($services as $service)
            <label><input type="checkbox" name="services[]"/> {{ $service->name }} (от {{ $service->price }} ₽)</label>
        @endforeach


        <label for="datetime">Дата и время</label>
        <input type="datetime-local" id="datetime" required />

        <label for="name">Имя</label>
        <input type="text" id="name" placeholder="Ваше имя" required />

        <label for="phone">Телефон</label>
        <input type="tel" id="phone" placeholder="+7 (___) ___-__-__" required />

        <div class="total" id="totalPrice">Итого: 0 ₽</div>
        <div class="total" id="totalTime">Примерное время: —</div>

        <button type="submit">Оформить заказ</button>
    </form>

</div>
</body>
</html>
