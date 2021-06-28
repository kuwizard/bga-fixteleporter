<?php
namespace Teleporter\Helpers;
use fixtheteleporter;

class Notifications
{
  private static function notifyAll($name, $msg, $data)
  {
    fixtheteleporter::get()->notifyAllPlayers($name, $msg, $data);
  }

  public static function rotateTile($pId, $from, $to)
  {
    $data = [
      'player_id' => $pId,
      'from' => $from,
      'to' => $to,
    ];
    self::notifyAll('rotateTile', '', $data);
  }
}