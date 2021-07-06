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

  public static function pickNextCard()
  {
    if (Globals::getCurrentCardId() === sizeof(self::$cards))
    {
      // This should not ever happen unless players make mistakes ALL THE TIME
      Globals::resetCardId();
    }
    Globals::pickNextCard();
  }

  private static $cards = [
    [1, 2, 3, 4],
    [1, 2, 3, 8],
    [1, 2, 4, 7],
    [1, 2, 7, 8],
    [1, 3, 4, 6],
    [1, 3, 6, 8],
    [1, 4, 6, 7],
    [1, 6, 7, 8],
    [2, 3, 4, 5],
    [2, 3, 5, 8],
    [2, 4, 5, 7],
    [2, 5, 7, 8],
    [3, 4, 5, 6],
    [3, 5, 6, 8],
    [4, 5, 6, 7],
    [5, 6, 7, 8],
    [1, 2, 4, 3],
    [1, 2, 8, 3],
    [1, 2, 7, 4],
    [1, 2, 8, 7],
    [1, 3, 6, 4],
    [1, 3, 8, 6],
    [1, 4, 7, 6],
    [1, 6, 8, 7],
    [2, 3, 5, 4],
    [2, 3, 8, 5],
    [2, 4, 7, 5],
    [2, 5, 8, 7],
    [3, 4, 6, 5],
    [3, 5, 8, 6],
    [4, 5, 7, 6],
    [5, 6, 8, 7],
    [2, 1, 4, 3],
    [2, 1, 8, 3],
    [2, 1, 7, 4],
    [2, 1, 8, 7],
    [3, 1, 6, 4],
    [3, 1, 8, 6],
    [4, 1, 7, 6],
    [6, 1, 8, 7],
    [3, 2, 5, 4],
    [3, 2, 8, 5],
    [4, 2, 7, 5],
    [5, 2, 8, 7],
    [4, 3, 6, 5],
    [5, 3, 8, 6],
    [5, 4, 7, 6],
    [6, 5, 8, 7],
    [2, 1, 3, 4],
    [2, 1, 3, 8],
    [2, 1, 4, 7],
    [2, 1, 7, 8],
    [3, 1, 4, 6],
    [3, 1, 6, 8],
    [4, 1, 6, 7],
    [6, 1, 7, 8],
    [3, 2, 4, 5],
    [3, 2, 5, 8],
    [4, 2, 5, 7],
    [5, 2, 7, 8],
    [4, 3, 5, 6],
    [5, 3, 6, 8],
    [5, 4, 6, 7],
    [6, 5, 7, 8],
    [3, 4, 1, 2],
    [3, 8, 1, 2],
    [4, 7, 1, 2],
    [7, 8, 1, 2],
    [4, 6, 1, 3],
    [6, 8, 1, 3],
    [6, 7, 1, 4],
    [7, 8, 1, 6],
    [4, 5, 2, 3],
    [5, 8, 2, 3],
    [5, 7, 2, 4],
    [7, 8, 2, 5],
    [5, 6, 3, 4],
    [6, 8, 3, 5],
    [6, 7, 4, 5],
    [7, 8, 5, 6],
    [3, 4, 2, 1],
    [3, 8, 2, 1],
    [4, 7, 2, 1],
    [7, 8, 2, 1],
    [4, 6, 3, 1],
    [6, 8, 3, 1],
    [6, 7, 4, 1],
    [7, 8, 6, 1],
    [4, 5, 3, 2],
    [5, 8, 3, 2],
    [5, 7, 4, 2],
    [7, 8, 5, 2],
    [5, 6, 4, 3],
    [6, 8, 5, 3],
    [6, 7, 5, 4],
    [7, 8, 6, 5],
    [4, 3, 2, 1],
    [8, 3, 2, 1],
    [7, 4, 2, 1],
    [8, 7, 2, 1],
    [6, 4, 3, 1],
    [8, 6, 3, 1],
    [7, 6, 4, 1],
    [8, 7, 6, 1],
    [5, 4, 3, 2],
    [8, 5, 3, 2],
    [7, 5, 4, 2],
    [8, 7, 5, 2],
    [6, 5, 4, 3],
    [8, 6, 5, 3],
    [7, 6, 5, 4],
    [8, 7, 6, 5],
    [4, 3, 1, 2],
    [8, 3, 1, 2],
    [7, 4, 1, 2],
    [8, 7, 1, 2],
    [6, 4, 1, 3],
    [8, 6, 1, 3],
    [7, 6, 1, 4],
    [8, 7, 1, 6],
    [5, 4, 2, 3],
    [8, 5, 2, 3],
    [7, 5, 2, 4],
    [8, 7, 2, 5],
    [6, 5, 3, 4],
    [8, 6, 3, 5],
    [7, 6, 4, 5],
    [8, 7, 5, 6],
  ];
}
