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
      ->orderBy('position')
      ->get()
      ->toArray();
    return array_map(function ($el) {
      return (int) $el['type'];
    }, $result);
  }

  public static function flip($playerId, $tileType)
  {
    $newTileType = $tileType < 5 ? $tileType + 4 : $tileType - 4;
    self::DB()
      ->update(['type' => $newTileType])
      ->where('player_id', $playerId)
      ->where('type', $tileType)
      ->run();
    return $newTileType;
  }

  public static function getTileType($playerId, $position)
  {
    $result = self::DB()
      ->select(['type'])
      ->where('player_id', $playerId)
      ->where('position', $position)
      ->getSingle();
    return (int) $result['type'];
  }

  public static function change($playerId, $positions)
  {
    // Sort positions in descending and tiles in ascending order to make sure they interchange
    rsort($positions);
    $tilesToChangeIds = self::DB()
      ->select(['tile_id'])
      ->where('player_id', $playerId)
      ->whereIn('position', $positions)
      ->orderBy('position')
      ->get()
      ->getIds();
    foreach ($tilesToChangeIds as $tileId) {
      self::DB()
        ->update(['position' => array_shift($positions)])
        ->where('tile_id', $tileId)
        ->run();
    }
  }
}
