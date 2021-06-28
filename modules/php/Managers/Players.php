<?php
namespace Teleporter\Managers;
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
    $query = self::DB()->multipleInsert([
      'player_id',
      'player_color',
      'player_canal',
      'player_name',
      'player_avatar',
    ]);

    $values = [];
    foreach ($players as $pId => $player) {
      $color = array_pop($gameInfos['player_colors']);
      $canal = $player['player_canal'];
      $avatar = addslashes($player['player_avatar']);
      $name = addslashes($player['player_name']);
      $values[] = [$pId, $color, $canal, $name, $avatar];
    }
    $query->values($values);
    self::getGame()->reattributeColorsBasedOnPreferences($players, $colors);
    self::getGame()->reloadPlayersBasicInfos();
  }

  public static function getActiveId()
  {
    return self::getGame()->getActivePlayerId();
  }

  public static function getCurrentId()
  {
    return self::getGame()->getCurrentPId();
  }

  public static function getAll()
  {
    return self::DB()->get(false);
  }

  public static function get($pId = null)
  {
    $pId = $pId ?: self::getActiveId();
    return self::DB()
      ->where($pId)
      ->getSingle();
  }

  public static function getActive()
  {
    return self::get();
  }

  public static function getCurrent()
  {
    return self::get(self::getCurrentId());
  }

  public static function getUiData()
  {
    return self::getAll()->map(function ($player) {
      return $player->getUiData();
    });
  }
}