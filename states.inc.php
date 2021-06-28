<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * fixtheteleporter implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 * 
 * states.inc.php
 *
 * fixtheteleporter game states description
 *
 */
 
$machinestates = [
    1 => [
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => ['' => 2]
    ],

    2 => [
    		"name" => "playerTurn",
    		"description" => clienttranslate('${actplayer} must play a card or pass'),
    		"descriptionmyturn" => clienttranslate('${you} must play a card or pass'),
    		"type" => "activeplayer",
    		"possibleactions" => ['actRotate'],
    		"transitions" => [2 => 2]
    ],

    99 => [
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd",
      ]
];



