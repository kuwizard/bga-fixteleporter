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
      self::insert($varName, 1);
    } else {
      self::update($varName, (int)$currentCard['value'] + 1);
    }
  }

  public static function resetCardId()
  {
    self::update('currentCard', 1);
  }

  public static function getCurrentCardId()
  {
    return (int)self::getCurrentCard()['value'];
  }

  public static function getPlayerIdToCheck()
  {
    return self::get('player');
  }

  public static function setPlayerToCheck($playerId = 0)
  {
    self::update('player', $playerId);
  }

  public static function initPlayerVar()
  {
    self::insert('player', 0);
  }

  private static function getCurrentCard()
  {
    return self::get('currentCard', false);
  }

  private static function insert($name, $value)
  {
    self::DB()
      ->insert([
        'name' => $name,
        'value' => $value,
      ]);
  }

  private static function get($name, $getValue = true)
  {
    $valueArray =  self::DB()
      ->select(['value'])
      ->where('name', $name)
      ->get(true);
    return $getValue ? (int) $valueArray['value'] : $valueArray;
  }

  private static function update($name, $value)
  {
    self::DB()
      ->update(['value' => $value])
      ->where('name', $name)
      ->run();
  }
}
