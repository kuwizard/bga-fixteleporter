<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * fixtheteleporter implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * fixtheteleporter.action.php
 *
 * fixtheteleporter main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/fixtheteleporter/fixtheteleporter/myAction.html", ...)
 *
 */
  
  
  class action_fixtheteleporter extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "fixtheteleporter_fixtheteleporter";
            self::trace( "Complete reinitialization of board game" );
      }
  	}

    public function actFlip()
    {
      self::setAjaxMode();
      $this->game->actFlip(self::getInt('position'));
      self::ajaxResponse();
    }

    public function actChange()
    {
      self::setAjaxMode();
      $positions = array_map('intval', explode(';', self::getArg('positions', AT_numberlist, false)));
      $this->game->actChange($positions);
      self::ajaxResponse();
    }

    public function actDone()
    {
      self::setAjaxMode();
      $this->game->actDone();
      self::ajaxResponse();
    }

    private function getInt($var)
    {
      $arg = self::getArg($var, AT_alphanum, false);
      return is_null($arg) ? null : (int) self::getArg($var, AT_alphanum, false);
    }
  }
  

