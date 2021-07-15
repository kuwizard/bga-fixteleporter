
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- Please Fix the Teleporter implementation : © Pavel Kulagin kuzwiz@mail.ru
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

ALTER TABLE `player` ADD `player_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT true;

CREATE TABLE IF NOT EXISTS `tile` (
    `tile_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `player_id` INT(10) NOT NULL,
    `type` INT(1) NOT NULL,
    `position` INT(1) NOT NULL,
    PRIMARY KEY (`tile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `card` (
    `card_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `tile_1` INT(1) NOT NULL,
    `tile_2` INT(1) NOT NULL,
    `tile_3` INT(1) NOT NULL,
    `tile_4` INT(1) NOT NULL,
    PRIMARY KEY (`card_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `global_variables` (
    `name` VARCHAR(50) NOT NULL,
    `value` INT(2) NOT NULL,
    PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;