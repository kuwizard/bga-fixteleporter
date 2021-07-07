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
 * fixtheteleporter.game.php
 *
 * This is the main file for your game logic.
 *
 * In this PHP file, you are going to defines the rules of the game.
 *
 */

use Teleporter\Managers\Cards;
use Teleporter\Managers\Globals;
use Teleporter\Managers\Players;
use Teleporter\Managers\Tiles;

$swdNamespaceAutoload = function ($class) {
  $classParts = explode('\\', $class);
  if ($classParts[0] == 'Teleporter') {
    array_shift($classParts);
    $file = dirname(__FILE__) . '/modules/php/' . implode(DIRECTORY_SEPARATOR, $classParts) . '.php';
    if (file_exists($file)) {
      require_once $file;
    } else {
      var_dump("Impossible to load class: $class");
    }
  }
};
spl_autoload_register($swdNamespaceAutoload, true, true);

require_once APP_GAMEMODULE_PATH . 'module/table/table.game.php';

class fixtheteleporter extends Table
{
  use Teleporter\States\FixTeleporterTrait;
  use Teleporter\States\CheckTrait;
  public static $instance = null;
  public function __construct()
  {
    parent::__construct();
    self::$instance = $this;
    self::initGameStateLabels([]);
  }
  public static function get()
  {
    return self::$instance;
  }

  protected function getGameName()
  {
    return 'fixtheteleporter';
  }

  protected function setupNewGame($players, $options = [])
  {
    Players::setupNewGame($players);
    Tiles::setupNewGame($players);
    Cards::setupNewGame();
    Globals::initPlayerVar();
    Cards::pickNextCard();
  }

  protected function getAllDatas()
  {
    return [
      'players' => Players::getUiData()->toAssoc(),
      'players_ordered' => Players::getUiData()->toArray(),
      'card' => Cards::getCurrentCardValues(),
    ];
  }

  function getGameProgression()
  {
    $players = Players::getAll();
    $scoresArray = $players
      ->map(function ($player) {
        return $player->getScore();
      })
      ->toArray();
    $scoresSum = array_sum($scoresArray);
    $maxScoreSum = count($scoresArray) * 4 + 1;
    $progression = ($scoresSum * 100) / $maxScoreSum;
    //        return $progression; // TODO: Uncomment this line before beta and remove next one
    return 50 + $progression / 2;
  }

  function zombieTurn($state, $active_player)
  {
    $statename = $state['name'];
    if ($state['type'] === 'multipleactiveplayer') {
      // Make sure player is in a non blocking status for role turn
      $this->gamestate->setPlayerNonMultiactive($active_player, '');
      return;
    }
    throw new feException('Zombie mode not supported at this game state: ' . $statename);
  }

  /////////////////////////////////////////////////////////////
  // Exposing protected methods, please use at your own risk //
  /////////////////////////////////////////////////////////////

  // Exposing protected method getCurrentPlayerId
  public static function getCurrentPId()
  {
    return self::getCurrentPlayerId();
  }

  ///////////////////////////////////////////////////////////////////////////////////:
  ////////// DB upgrade
  //////////

  /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */

  function upgradeTableDb($from_version)
  {
    // $from_version is the current version of this game database, in numerical form.
    // For example, if the game was running with a release of your game named "140430-1345",
    // $from_version is equal to 1404301345

    // Example:
    //        if( $from_version <= 1404301345 )
    //        {
    //            // ! important ! Use DBPREFIX_<table_name> for all tables
    //
    //            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
    //            self::applyDbUpgradeToAllDB( $sql );
    //        }
    //        if( $from_version <= 1405061421 )
    //        {
    //            // ! important ! Use DBPREFIX_<table_name> for all tables
    //
    //            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
    //            self::applyDbUpgradeToAllDB( $sql );
    //        }
    //        // Please add your future database scheme changes here
    //
    //
  }
}
