<?php
namespace Teleporter\Managers;

/*
 * Cards: all utility functions concerning cards are here
 */
class Cards extends \Teleporter\Helpers\Pieces
{
  protected static $table = 'card';
  protected static $prefix = 'card_';
  protected static $customFields = ['tile_1', 'tile_2', 'tile_3', 'tile_4'];

  private static $cards = [
    [1, 2, 3, 4],
    [1, 2, 4, 3],
    [2, 1, 3, 4],
    [2, 1, 4, 3],
    [3, 4, 1, 2],
    [3, 4, 2, 1],
    [4, 3, 1, 2],
    [4, 3, 2, 1],
    [1, 3, 2, 4],
    [1, 3, 4, 2],
    [3, 1, 2, 4],
    [3, 1, 4, 2],
    [1, 4, 2, 3],
    [1, 4, 3, 2],
    [1, 6, 3, 4],
    [1, 6, 4, 3],
    [5, 2, 3, 4],
    [5, 2, 4, 3],
    [1, 2, 7, 4],
    [1, 2, 4, 7],
    [1, 2, 3, 8],
    [1, 2, 8, 3],
    [5, 2, 3, 8],
    [5, 2, 8, 3],
  ];

  public static function setupNewGame()
  {
    shuffle(self::$cards);
    foreach (self::$cards as $card) {
      self::DB()->multipleInsert(self::$customFields)->values([$card]);
    }
  }

  public static function getCurrentCardValues()
  {
    $currentCardId = Globals::getCurrentCardId();
    $currentCardValues = self::DB()
      ->select(self::$customFields)
      ->where('card_id', $currentCardId)
      ->getSingle();
    return array_map(function ($value) {
      return (int) $value;
    }, array_values($currentCardValues));
  }
}
