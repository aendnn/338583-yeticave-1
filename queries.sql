-- Добавляет категории

INSERT INTO `categories` (`name`) VALUES
('Доски и лыжи'),
('Крепления'),
('Ботинки'),
('Одежда'),
('Инструменты'),
('Разное');

-- Добавляет пользователей

INSERT INTO `users` (`email`, `password`, `name`, `avatar`, `dt_add`, `contacts`, `lots_id`, `bids`) VALUES
('vasiliy85@gmail.com', 'qwerty', 'Василий', 'img/user.jpg', '2018-11-28 11:33:12', 'Telegram: vasya123', 1, 1),
('anna99@gmail.com', '123456anna', 'Анна', 'img/user.jpg', '2018-11-28 11:36:20', 'Email: ann34@gmail.com', 3, 2);

-- Добавляет лоты

INSERT INTO `lots` (`pic`, `cat_id`, `user_id`, `winner`, `title`, `desc`, `primary_price`, `price`, `date_create`, `date_end`, `step_bid`) VALUES
('img/lot-1.jpg', 1, 1, 1, '2014 Rossignol District Snowboard', 'desc', 10999, 12000, '2018-11-28 18:30:20', '2018-11-29 00:00:00', 200),
('img/lot-2.jpg', 1, 2, 2, 'DC Ply Mens 2016/2017 Snowboard', 'desc', 159999, 200000, '2018-11-28 14:00:00', '2018-11-29 00:00:00', 100),
('img/lot-3.jpg', 2, 2, 4, 'Крепления Union Contact Pro 2015 года размер L/XL', 'desc', 8000, 9000, '2018-11-28 15:00:00', '2018-11-29 00:00:00', 40),
('img/lot-4.jpg', 3, 1, 3, 'Ботинки для сноуборда DC Mutiny Charocal', 'desc', 10999, 12000, '2018-11-28 16:00:00', '2018-11-29 00:00:00', 450),
('img/lot-5.jpg', 4, 2, 5, 'Куртка для сноуборда DC Mutiny Charocal', 'desc', 7500, 7600, '2018-11-28 10:00:00', '2018-11-29 00:00:00', 222),
('img/lot-6.jpg', 5, 1, 2, 'Маска Oakley Canopy', 'desc', 5400, 5500, '2018-11-28 13:00:00', '2018-11-29 00:00:00', 200);

UPDATE `lots` SET `desc` = 'Доска средней жёсткости' WHERE `id` = 1;
UPDATE `lots` SET `desc` = 'Доска средней жёсткости' WHERE `id` = 2;
UPDATE `lots` SET `desc` = 'Крепление для горных лыж' WHERE `id` = 3;
UPDATE `lots` SET `desc` = 'Жёсткие ботинки — воплощение универсальности, подходящее как для катания по трассам, так и для заездов в парке и бэккантри' WHERE `id` = 4;
UPDATE `lots` SET `desc` = 'Сноубордическая куртка. Эргономичный крой' WHERE `id` = 5;
UPDATE `lots` SET `desc` = 'Обеспечивает кристально-чистое изображение вне зависимости от погодных условий и исключает запотевание линзы' WHERE `id` = 6;

-- Добавляет ставки

INSERT INTO `bids` (`date_bid`, `sum_bid`, `user_id`, `lot_id`) VALUES
('2018-11-28 12:03:12', 1200, 1, 2),
('2018-11-28 12:05:12', 2500, 2, 4);

-- Получить все категории и сортировать по возрастанию id

SELECT * FROM `categories`
ORDER BY `categories`.`id` ASC;

-- Получить самые новые, открытые лоты

SELECT `lots`.`title`, `primary_price`, `pic`, `categories`.`name` AS `cat_name` FROM `lots`
INNER JOIN `categories` ON `lots`.`cat_id` = `categories`.`id`
WHERE `lots`.`date_end` != CURRENT_DATE()
ORDER BY `lots`.`date_create` DESC LIMIT 6;

-- Показать лот по его id

SELECT `title`, `primary_price`, `pic`, `price`, `categories`.`id`, `categories`.`name` FROM `lots`
INNER JOIN `categories` ON `lots`.`cat_id` = `categories`.`id`
WHERE `lots`.`id` = 2;

-- Обновить название лота по его идентификатору

UPDATE `lots` SET `title` = '2014 Rossignol'
WHERE `lots`.`id` = 1;

-- Получить список самых свежих ставок для лота по его идентификатору

SELECT `lots`.`id`, `lots`.`title`, `categories`.`name`, `bids`.`date_bid`, `bids`.`sum_bid`, `users`.`name` FROM `lots`
INNER JOIN `categories` ON `lots`.`cat_id` = `categories`.`id`
LEFT JOIN `bids` ON `lots`.`id` = `bids`.`lot_id`
INNER JOIN `users` ON `bids`.`user_id` = `users`.`id`
WHERE `lots`.`id` = 2
ORDER BY `bids`.`date_bid` DESC LIMIT 6;

INSERT INTO `lots` (`pic`, `cat_id`, `user_id`, `winner`, `title`, `desc`, `primary_price`, `price`, `date_create`, `date_end`, `step_bid`) VALUES
('img/lot-1.jpg', 1, 1, 1, '2014 Rossignol District Snowboard', 'desc', 100999, 40000, '2019-02-09 15:08:20', '2019-11-29 00:00:00', 200);
