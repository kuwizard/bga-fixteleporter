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
    ST_GAME_SETUP => [
        'name' => 'gameSetup',
        'description' => '',
        'type' => 'manager',
        'action' => 'stGameSetup',
        'transitions' => ['' => ST_FIX_TELEPORTER]
    ],

    ST_FIX_TELEPORTER => [
    		'name' => 'playerTurn',
    		'description' => clienttranslate('${actplayer} must fix the teleporter'),
    		'descriptionmyturn' => clienttranslate('${you} must fix the teleporter'),
    		'type' => 'multipleactiveplayer',
        'action' => 'stMakeEveryoneActive',
    		'possibleactions' => ['actFlip', 'actChange', 'actDone'],
    		'transitions' => ['' => ST_CHECK]
    ],

    ST_CHECK => [
      'name' => 'check',
      'description' => clienttranslate('Checking if card matches...'),
      'type' => 'game',
      'action' => 'stCheck',
      'updateGameProgression' => true,
      'transitions' => [
        ST_FIX_TELEPORTER => ST_FIX_TELEPORTER,
        ST_GAME_END => ST_GAME_END
      ]
    ],

    ST_GAME_END => [
        'name' => 'gameEnd',
        'description' => clienttranslate('End of game'),
        'type' => 'manager',
        'action' => 'stGameEnd',
        'args' => 'argGameEnd',
      ]
];



