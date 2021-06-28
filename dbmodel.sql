
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- fixtheteleporter implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

CREATE TABLE IF NOT EXISTS `tile` (
    `tile_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `player_id` INT(10) NOT NULL,
    `type` INT(1) NOT NULL,
    `position` INT(1) NOT NULL,
    PRIMARY KEY (`tile_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;