<?php

namespace Teleporter\States;

use Teleporter\Helpers\Notifications;
use Teleporter\Managers\Tiles;

trait ChangeTileTrait
{
  public function actFlip($pId, $position)
  {
    $oldTileType = Tiles::getTileType($pId, $position);
    $newTileType = Tiles::flip($pId, $oldTileType);
    Notifications::flipTile($pId, $oldTileType, $newTileType);
  }

  public function actChange($pId, $positions)
  {
    Tiles::change($pId, $positions);
    Notifications::changeTiles($pId, $positions);
  }
}