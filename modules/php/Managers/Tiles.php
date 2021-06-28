<?php
namespace Teleporter\Managers;

/*
 * Cards: all utility functions concerning cards are here
 */
class Tiles extends \Teleporter\Helpers\Pieces
{
  protected static $table = 'tile';
  protected static $prefix = 'tile_';
  protected static $customFields = ['position'];

  public static function setupNewGame($players)
  {
    foreach (array_keys($players) as $playerId) {
      for ($i = 1; $i <= 4; $i++) {
        self::DB()->insert([
          'player_id' => $playerId,
          'type' => $i,
          'position' => $i - 1,
        ]);
      }
    }
  }

  public static function getHand($playerId)
  {
    $result = self::DB()
      ->select(['position', 'type'])
      ->where('player_id', $playerId)
      ->orderBy('position')->get()->toArray();
    return array_map(function ($el) {
      return $el['type'];
    }, $result);
  }

  public static function rotate($pId, $tileType)
  {
    if ($tileType < 5) {
      $newTileType = $tileType + 4;
    } else {
      $newTileType = $tileType - 4;
    }
    self::DB()
      ->update(['type' => $newTileType])
      ->where('player_id', $pId)
      ->where('type', $tileType)
      ->run();
    return $newTileType;
  }

  public static function getTileType($pId, $position)
  {
    $result = self::DB()
      ->select(['type'])
      ->where('player_id', $pId)
      ->where('position', $position)
      ->getSingle();
    return (int) $result['type'];
  }
}
