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
    $playerId = Globals::getPlayerIdToCheck();
    $player = Players::get($playerId);
    $suggestedValues = $player->getHand();
    $correctValues = Cards::getCurrentCardValues();
    Players::setAllActive();
    if ($correctValues === $suggestedValues) {
      Players::givePointTo($player);
    } else {
      Players::setNotActive($player);
    }
    $result = array_map(
      function ($value, $key) use ($suggestedValues, $correctValues) {
        return $value === $correctValues[$key];
      },
      $suggestedValues,
      array_keys($suggestedValues)
    );
    Cards::pickNextCard();
    Notifications::matchChecked($player, $result);
    if (Players::didSomeoneWin()) {
      $this->gamestate->nextState(ST_GAME_END);
    } else {
      $this->gamestate->nextState(ST_FIX_TELEPORTER);
    }
  }
}
