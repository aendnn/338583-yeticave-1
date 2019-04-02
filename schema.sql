CREATE DATABASE yeti
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE yeti;

CREATE TABLE `categories` (
`id` INT AUTO_INCREMENT,
`name` CHAR(128) NOT NULL,
PRIMARY KEY (`id`)
);

CREATE UNIQUE INDEX cat_name ON `categories`(`name`);

CREATE TABLE `users` (
`id` INT AUTO_INCREMENT,
`email` CHAR(128) NOT NULL,
`password` CHAR(64),
`name` CHAR(64),
`avatar` CHAR(128),
`dt_add` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`contacts` CHAR(128),
`lots_id` INT,
`bids` INT,
PRIMARY KEY (`id`)
);

CREATE UNIQUE INDEX user_email ON `users`(`email`);
CREATE INDEX is_avatar ON `users`(`avatar`);
CREATE INDEX list_lots ON `users`(`lots_id`);
CREATE INDEX bids_list ON `users`(`bids`);
CREATE INDEX contacts_list ON `users`(`contacts`);

CREATE TABLE `lots` (
`id` INT AUTO_INCREMENT,
`pic` CHAR(128),
`cat_id` INT NOT NULL,
`user_id` INT,
`winner` INT,
`title` CHAR(128),
`desc` TEXT,
`primary_price` INT,
`price` INT,
`date_create` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`date_end` DATETIME,
`step_bid` INT,
PRIMARY KEY (`id`)
);

CREATE INDEX title_lot ON `lots`(`title`);
CREATE INDEX primary_price_ind ON `lots`(`primary_price`);
CREATE INDEX date_create_ind ON `lots`(`date_create`);
CREATE INDEX date_end_ind ON `lots`(`date_end`);

CREATE TABLE `bids` (
`id` INT AUTO_INCREMENT,
`date_bid` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`sum_bid` INT NOT NULL,
`user_id` INT,
`lot_id` INT,
PRIMARY KEY (`id`)
);

CREATE INDEX date_bid_ind ON `bids`(`date_bid`);
CREATE INDEX bid_ind ON `bids`(`sum_bid`);
CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE `categories` (
`id` INT AUTO_INCREMENT,
`name` CHAR(128) NOT NULL,
PRIMARY KEY (`id`)
);

CREATE UNIQUE INDEX cat_name ON `categories`(`name`);

CREATE TABLE `users` (
`id` INT AUTO_INCREMENT,
`email` CHAR(128) NOT NULL,
`password` CHAR(64),
`name` CHAR(64),
`avatar` CHAR(128),
`dt_add` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`contacts` CHAR(128),
`lots_id` INT,
`bids` INT,
PRIMARY KEY (`id`)
);

CREATE UNIQUE INDEX user_email ON `users`(`email`);
CREATE INDEX is_avatar ON `users`(`avatar`);
CREATE INDEX list_lots ON `users`(`lots_id`);
CREATE INDEX bids_list ON `users`(`bids`);
CREATE INDEX contacts_list ON `users`(`contacts`);

CREATE TABLE `lots` (
`id` INT AUTO_INCREMENT,
`pic` CHAR(128),
`cat_id` INT NOT NULL,
`user_id` INT,
`winner` INT,
`title` CHAR(128),
`desc` TEXT,
`primary_price` INT,
`price` INT,
`date_create` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`date_end` DATETIME,
`step_bid` INT,
PRIMARY KEY (`id`)
);

CREATE INDEX title_lot ON `lots`(`title`);
CREATE INDEX primary_price_ind ON `lots`(`primary_price`);
CREATE INDEX date_create_ind ON `lots`(`date_create`);
CREATE INDEX date_end_ind ON `lots`(`date_end`);

CREATE TABLE `bids` (
`id` INT AUTO_INCREMENT,
`date_bid` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`sum_bid` INT NOT NULL,
`user_id` INT,
`lot_id` INT,
PRIMARY KEY (`id`)
);

CREATE INDEX date_bid_ind ON `bids`(`date_bid`);
CREATE INDEX bid_ind ON `bids`(`sum_bid`);
CREATE FULLTEXT INDEX search ON lots(`title`,`desc`);
