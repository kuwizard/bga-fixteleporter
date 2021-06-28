<?php
namespace Teleporter\Helpers;
use fixtheteleporter;

class Notifications
{
  private static function notifyAll($name, $msg, $data)
  {
    fixtheteleporter::get()->notifyAllPlayers($name, $msg, $data);
  }

  public static function rotateTile($name, $msg, $data)
  {
    fixtheteleporter::get()->notifyPlayer('rotateTile', $msg, $data);
  }
}