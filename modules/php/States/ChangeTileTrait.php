<?php

namespace Teleporter\States;

use Teleporter\Managers\Tiles;

trait ChangeTileTrait
{
  public function actRotate($pId, $position)
  {
    Tiles::rotate($pId, $position);
  }
}