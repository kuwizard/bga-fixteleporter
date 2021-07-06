<?php
namespace Teleporter\Helpers;
use fixtheteleporter;
use Teleporter\Managers\Cards;

class Notifications
{
  private static function notifyAll($name, $data, $msg = '')
  {
    self::updateArgs($data);
    fixtheteleporter::get()->notifyAllPlayers($name, $msg, $data);
  }

  public static function flipTile($playerId, $from, $to)
  {
    $data = [
      'player_id' => $playerId,
      'from' => $from,
      'to' => $to,
    ];
    self::notifyAll('flipTile', $data);
  }

  public static function changeTiles($playerId, $positions)
  {
    $data = [
      'player_id' => $playerId,
      'positions' => $positions,
    ];
    self::notifyAll('changeTiles', $data);
  }

  public static function matchChecked($player, $mistakes)
  {
    if (in_array(false, $mistakes)) {
      $msg = clienttranslate('${player_name} does NOT match the card correctly and may not participate in the next round');
    } else {
      $msg = clienttranslate('${player_name} matches the card and claims it');
    }
    self::notifyAll('matchChecked', ['player' => $player, 'mistakes' => $mistakes, 'newCard' => Cards::getCurrentCardValues()], $msg);
  }

  public static function playerClaimedFinish($player)
  {
    self::notifyAll('playerClaimedFinish', ['player' => $player]);
  }

  private static function updateArgs(&$data)
  {
    if (isset($data['player'])) {
      $data['player_id'] = $data['player']->getId();
      $data['player_name'] = $data['player']->getName();
      unset($data['player']);
    }
  }
}