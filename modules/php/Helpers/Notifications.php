<?php
namespace Teleporter\Helpers;
use fixtheteleporter;

class Notifications
{
  private static function notifyAll($name, $data, $msg = '')
  {
    fixtheteleporter::get()->notifyAllPlayers($name, $msg, $data);
  }

  public static function flipTile($pId, $from, $to)
  {
    $data = [
      'player_id' => $pId,
      'from' => $from,
      'to' => $to,
    ];
    self::notifyAll('flipTile', $data);
  }

  public static function changeTiles($pId, $positions)
  {
    $data = [
      'player_id' => $pId,
      'positions' => $positions,
    ];
    self::notifyAll('changeTiles', $data);
  }
}