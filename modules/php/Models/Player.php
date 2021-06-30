<?php
namespace Teleporter\Models;

use Teleporter\Managers\Tiles;

class Player
{
  protected $id;
  protected $no; // natural order
  protected $name; // player name
  protected $color;
  protected $score;

  public function __construct($row)
  {
    if ($row != null) {
      $this->id = (int) $row['player_id'];
      $this->no = (int) $row['player_no'];
      $this->name = $row['player_name'];
      $this->color = $row['player_color'];
      $this->score = (int) $row['player_score'];
    }
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getScore()
  {
    return $this->score;
  }

  public function getUiData()
  {
    return [
      'id' => $this->id,
      'no' => $this->no,
      'name' => $this->name,
      'color' => $this->color,
      'score' => $this->score,
      'hand' => $this->getHand(),
    ];
  }

  public function getHand()
  {
    return Tiles::getHand($this->id);
  }
}