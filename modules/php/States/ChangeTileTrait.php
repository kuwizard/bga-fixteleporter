<?php

namespace Teleporter\States;

use Teleporter\Helpers\Notifications;
use Teleporter\Managers\Tiles;

trait ChangeTileTrait
{
  public function actRotate($pId, $position)
  {
    $oldTileType = Tiles::getTileType($pId, $position);
    $newTileType = Tiles::rotate($pId, $oldTileType);
    Notifications::rotateTile($pId, $oldTileType, $newTileType);
  }
}