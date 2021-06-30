<?php

namespace Teleporter\States;

use Teleporter\Helpers\Notifications;
use Teleporter\Managers\Globals;
use Teleporter\Managers\Tiles;

trait FixTeleporterTrait
{
  public function actFlip($playerId, $position)
  {
    $oldTileType = Tiles::getTileType($playerId, $position);
    $newTileType = Tiles::flip($playerId, $oldTileType);
    Notifications::flipTile($playerId, $oldTileType, $newTileType);
  }

  public function actChange($playerId, $positions)
  {
    Tiles::change($playerId, $positions);
    Notifications::changeTiles($playerId, $positions);
  }

  public function actDone($playerId)
  {
    Globals::setPlayerToCheck($playerId);
    $this->gamestate->nextState();
  }
}