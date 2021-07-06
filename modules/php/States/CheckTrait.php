<?php

namespace Teleporter\States;

use Teleporter\Helpers\Notifications;
use Teleporter\Managers\Cards;
use Teleporter\Managers\Globals;
use Teleporter\Managers\Players;

trait CheckTrait
{
  public function stCheck()
  {
    $playerId = Globals::getPlayerToCheck();
    $suggestedValues = Players::get($playerId)->getHand();
    $correctValues = Cards::getCurrentCardValues();
    if ($correctValues === $suggestedValues) {
      Players::givePointTo($playerId);
    }
    $result = array_map(function ($value, $key) use ($suggestedValues, $correctValues) {
      return $value === $correctValues[$key];
    }, $suggestedValues, array_keys($suggestedValues));
    Cards::pickNextCard();
    Notifications::matchChecked(Players::get($playerId), $result);
    $this->gamestate->nextState(ST_FIX_TELEPORTER);
  }
}