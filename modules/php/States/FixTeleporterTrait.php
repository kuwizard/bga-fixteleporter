<?php

namespace Teleporter\States;

use Teleporter\Helpers\Notifications;
use Teleporter\Managers\Globals;
use Teleporter\Managers\Players;
use Teleporter\Managers\Tiles;

trait FixTeleporterTrait
{
  public function stMakeActive()
  {
    $players = Players::getAll()->getIds();
    $nonActivePlayer = Players::getNotActive();
    $activePlayers = array_diff($players, [$nonActivePlayer]);
    foreach ($activePlayers as $activePlayerId) {
      self::giveExtraTime($activePlayerId);
    }
    $this->gamestate->setPlayersMultiactive($activePlayers, ST_FIX_TELEPORTER, true);
  }

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
    $player = Players::get($playerId);
    Notifications::playerClaimedFinish($player);
    Globals::setPlayerToCheck($playerId);
    $this->gamestate->nextState();
  }
}
