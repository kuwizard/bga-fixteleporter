<?php
namespace Teleporter\Managers;

/*
 * Cards: all utility functions concerning cards are here
 */
class Globals extends \Teleporter\Helpers\DB_Manager
{
  protected static $table = 'global_variables';
  protected static $primary = 'name';

  public static function pickNextCard()
  {
    $varName = 'currentCard';
    $currentCard = self::getCurrentCard();
    if ($currentCard === null) {
      self::DB()
        ->insert([
          'name' => $varName,
          'value' => 1,
        ]);
    } else {
      self::DB()
        ->update(['value' => (int) $currentCard['value'] + 1])
        ->where('name', $varName)
        ->run();
    }
  }

  private static function getCurrentCard()
  {
    return self::DB()
      ->select(['value'])
      ->where('name', 'currentCard')
      ->get(true);
  }

  public static function getCurrentCardId()
  {
    return (int) self::getCurrentCard()['value'];
  }
}
