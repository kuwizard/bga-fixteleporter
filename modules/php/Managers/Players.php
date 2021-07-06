<?php
namespace Teleporter\Managers;
use Teleporter\Helpers\Notifications;
use Teleporter\Models\Player;
use fixtheteleporter;

class Players extends \Teleporter\Helpers\DB_Manager
{
  protected static function getGame()
  {
    return fixtheteleporter::get();
  }

  protected static $table = 'player';
  protected static $primary = 'player_id';

  protected static function cast($row)
  {
    return new Player($row);
  }

  public static function setupNewGame($players)
  {
    $gameInfos = self::getGame()->getGameinfos();
    $colors = $gameInfos['player_colors'];
    $query = self::DB()->multipleInsert(['player_id', 'player_color', 'player_canal', 'player_name', 'player_avatar']);

    $values = [];
    foreach ($players as $playerId => $player) {
      $color = array_pop($gameInfos['player_colors']);
      $canal = $player['player_canal'];
      $avatar = addslashes($player['player_avatar']);
      $name = addslashes($player['player_name']);
      $values[] = [$playerId, $color, $canal, $name, $avatar];
    }
    $query->values($values);
    self::getGame()->reattributeColorsBasedOnPreferences($players, $colors);
    self::getGame()->reloadPlayersBasicInfos();
  }

  public static function getCurrentId()
  {
    return self::getGame()->getCurrentPId();
  }

  public static function getAll()
  {
    return self::DB()->get(false);
  }

  public static function getAllStartingWith($player)
  {
    return self::DB()
      ->orderBy("player_no < {$player->getNo()}, player_no")
      ->get();
  }

  public static function get($playerId = null)
  {
    $playerId = $playerId ?: self::getCurrentId();
    return self::DB()
      ->where($playerId)
      ->getSingle();
  }

  public static function getCurrent()
  {
    return self::get(self::getCurrentId());
  }

  public static function getUiData()
  {
    $players = self::getAllStartingWith(self::getCurrent());
    return $players->map(function ($player) {
      return $player->getUiData();
    });
  }

  public static function givePointTo($player)
  {
    $oldScore = $player->getScore();
    self::DB()
      ->update(['player_score' => $oldScore + 1])
      ->where('player_id', $player->getId())
      ->run();
  }

  public static function setNotActive($player)
  {
    self::DB()
      ->update(['player_active' => 0])
      ->where('player_id', $player->getId())
      ->run();
  }

  public static function setAllActive()
  {
    self::DB()
      ->update(['player_active' => 1])
      ->run();
  }

  public static function getNotActive()
  {
    $nonActive = self::DB()
      ->where('player_active', 0)
      ->get(true);
    return is_null($nonActive) ? $nonActive : $nonActive->getId();
  }

  public static function didSomeoneWin()
  {
    $winningPlayer = self::DB()
      ->where('player_score', 5)
      ->get(true);
    return !is_null($winningPlayer);
  }
}
