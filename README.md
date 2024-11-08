### Тестовое задание
* Это тестовое задание не имеет никаких технических ограничений. Ты можешь использовать любые инструменты.

### Задание №1
После успешной покупки билетов на событие, данные попадают в список заказов. Список заказов сохраняется в таблице MySql в виде:

id	event_id	event_date	ticket_adult_price	ticket_adult_quantity	ticket_kid_price	ticket_kid_quantity	barcode	user_id	equal_price	created
1	003	2021-08-21 13:00:00	700	1	450	0	11111111	00451	700	2021-01-11 13:22:09
2	006	2021-07-29 18:00:00	1000	0	800	2	22222222	00364	1600	2021-01-12 16:62:08
3	003	2021-08-15 17:00:00	700	4	450	3	33333333	00015	4150	2021-01-13 10:08:45
Где:

id - int(10) - инкрементальный порядковый номер заказа
event_id - int(11) - уникальный ид события. У каждого события есть свое название, описание, расписание, цены и свой уникальный event_id соответственно
event_date - varchar(10) - дата и время на которое были куплены билеты
ticket_adult_price - int(11) - цена взрослого билета на момент покупки
ticket_adult_quantity - int(11) - количество купленных взрослых билетов в этом заказе
ticket_kid_price - int(11) - цена детского билета на момент покупки
ticket_kid_quantity - int(11) - количество купленных детских билетов в этом заказе
barcode - varchar(120) - уникальный штрих код заказа
equal_price - int(11) - общая сумма заказа
created - datetime - дата создания заказа
Задача: написать функцию, которая будет добавлять заказы в эту таблицу.

Аргументы которые функция получает на входе: event_id, event_date, ticket_adult_price, ticket_adult_quantity, ticket_kid_price, ticket_kid_quantity

Нужно сгенерировать barcode, который будет уникальным со случайным набором цифр, он не должен быть порядковым.

Так же, существует некая сторонняя api.site.com. API писать не нужно, возвращаемые данные можно замокать и возвращать в случайном порядке. в которой нужно сделать бронь заказа отправив ей (https://api.site.com/book) event_id, event_date, ticket_adult_price, ticket_adult_quantity, ticket_kid_price, ticket_kid_quantity, barcode. На что она может вернуть либо {message: 'order successfully booked'}, либо {error: 'barcode already exists'}. В случае если получаем ошибку, нужно сгенерировать новый barcode и повторить попытку. Важно учесть, если запрос будет происходить одновременно, не должно возникнуть такой ситуации, что двум разным заказам присвоился один номер.

После успешной брони, нужно отправить на стороннюю апи запрос с подтверждением (https://api.site.com/approve), который принимает только barcode. Ответов может быть 2 варианта - успешный: {message: 'order successfully aproved'} и различные варианты ошибок {error: 'event cancelled'}, {error: 'no tickets'}, {error: 'no seats'}, {error: 'fan removed'}. В случае успеха, сохраняем заказ в БД.
### Ответ на задание №1. Реализация логики бронирования
### Установка:
1. Склонируйте репозиторий.
2. Установите зависимости с помощью Composer:
    ```bash
    composer install
    ```

3. Настройте параметры базы данных в `config/database.php`.

### Инициализация приложения
Инициализация приложения происходит в `bootstrap/app.php`, где создаются экземпляры необходимых классов и устанавливается соединение с базой данных.

### Запуск приложения
Для запуска используйте встроенный PHP сервер:
* php -S localhost:8000 -t public

### После запуска приложение будет доступно по адресу http://localhost:8000

### Задание 2: Нормализация таблицы для дополнительных типов билетов
#### Модификация структуры базы данных
1. Разделим основную таблицу заказов (orders) и таблицу билетов (tickets).
2. В таблице tickets для каждого билета будет уникальный баркод.
#### Новая структура базы данных:
* orders: содержит только данные заказа.
* tickets: содержит данные для каждого билета, включая тип и уникальный баркод.
#### таблицы к проекту прилагаются и также закомичены.



#### Задание 3: Документация решения.
#### Установка PHPUnit
* composer require --dev phpunit/phpunit
* Запустить тесты можно командой: php vendor/bin/phpunit tests/
* Краткое описание тестов:

* Тест создания заказа: Проверяет, что заказ создается с корректными параметрами и общей стоимостью, рассчитанной из количества и стоимости билетов.

* Тест генерации уникального баркода: Проверяет, что для каждого заказа создается уникальный баркод, и что при обнаружении дубликата OrderService создает новый баркод.

* Тест бронирования через API: Проверяет взаимодействие с внешним API при бронировании, эмулируя успешные и неудачные попытки (например, ошибку barcode already exists).

* Тест подтверждения заказа: Проверяет, что при успешном бронировании вызвано подтверждение заказа через API и заказ сохраняется в базе данных только после успешного подтверждения.
