CREATE TABLE `publicacoes` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`texto` MEDIUMTEXT NOT NULL,
    `user_id` INT NOT NULL,
	`created` DATETIME NULL,
	`modified` DATETIME NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
;


CREATE TABLE `curtidas` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`publicacao_id` INT NOT NULL,
    `user_id` INT NOT NULL,
	`created` DATETIME NULL,
	`modified` DATETIME NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
;

CREATE TABLE `user` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`login` VARCHAR (150) NOT NULL,
	`senha` VARCHAR (150) NOT NULL,
	`created` DATETIME NULL,
	`modified` DATETIME NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
;

CREATE TABLE `comentario_publicacoes` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`publicacao_id` INT NOT NULL,
	`user_id` INT NOT NULL,
    `comentario` TEXT NOT NULL,
	`created` DATETIME NULL,
	`modified` DATETIME NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci'
;




