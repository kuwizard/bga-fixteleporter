<?php

namespace Teleporter\States;

use Teleporter\Helpers\Notifications;
use Teleporter\Managers\Globals;
use Teleporter\Managers\Tiles;

trait FixTeleporterTrait
{
  public function actFlip($position)
  {
    $playerId = self::getCurrentPId();
    $oldTileType = Tiles::getTileType($playerId, $position);
    $newTileType = Tiles::flip($playerId, $oldTileType);
    Notifications::flipTile($playerId, $oldTileType, $newTileType);
  }

  public function actChange($positions)
  {
    $playerId = self::getCurrentPId();
    Tiles::change($playerId, $positions);
    Notifications::changeTiles($playerId, $positions);
  }

  public function actDone()
  {
    $playerId = self::getCurrentPId();
    Globals::setPlayerToCheck($playerId);
    $this->gamestate->nextState();
  }
}