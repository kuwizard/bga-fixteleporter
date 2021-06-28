/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * fixtheteleporter implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * fixtheteleporter.js
 *
 * fixtheteleporter user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter"
],
function (dojo, declare) {
    return declare("bgagame.fixtheteleporter", ebg.core.gamegui, {
        constructor: function(){
            console.log('fixtheteleporter constructor');
            this._notifications = [];
        },
        
        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );
            
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                if (player_id === this.gamedatas.gamestate.active_player) {
                    player.hand.forEach((type, position) => {
                        if (position % 2 === 0) {
                            dojo.place(this.format_block('jstpl_rotate', {mirror: false, position: position}), 'player_board');
                        }
                        dojo.place(this.format_block('jstpl_tile', {type: type, position: position}), 'player_board');
                        if (position % 2 === 1) {
                            dojo.place(this.format_block('jstpl_rotate', {mirror: true, position: position}), 'player_board');
                        }
                    });
                }
            }
            dojo.query('.rotate').forEach((rotateButton) => {
                dojo.connect(rotateButton, 'onclick', () => this.onClickRotate(rotateButton.dataset.position));
            })
            this.setupNotifications();
            console.log( "Ending game setup" );
        },
       

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Show some HTML block at this game state
                dojo.style( 'my_html_block_id', 'display', 'block' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
/*               
                 Example:
 
                 case 'myGameState':
                    
                    // Add 3 action buttons in the action status bar:
                    
                    this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' ); 
                    this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' ); 
                    this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' ); 
                    break;
*/
                }
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        onClickRotate(position) {
            this.takeAction('actRotate', {
                position: position,
            });
        },

        /*
         * Make an AJAX call with automatic lock
         */
        takeAction(action, data, reEnterStateOnError, checkAction = true) {
            if (checkAction && !this.checkAction(action)) return false;

            data = data || {};
            if (data.lock === undefined) {
                data.lock = true;
            } else if (data.lock === false) {
                delete data.lock;
            }
            let promise = new Promise((resolve, reject) => {
                this.ajaxcall(
                    '/' + this.game_name + '/' + this.game_name + '/' + action + '.html',
                    data,
                    this,
                    (data) => resolve(data),
                    (isError, message, code) => {
                        if (isError) reject(message, code);
                    },
                );
            });
        },

        setupNotifications: function()
        {
            console.log(this._notifications);
            this._notifications.forEach((notif) => {
                var functionName = 'notif_' + notif[0];

                dojo.subscribe(notif[0], this, functionName);
                if (notif[1] !== undefined) {
                    if (notif[1] === null) {
                        this.notifqueue.setSynchronous(notif[0]);
                    } else {
                        this.notifqueue.setSynchronous(notif[0], notif[1]);

                        // xxxInstant notification runs same function without delay
                        dojo.subscribe(notif[0] + 'Instant', this, functionName);
                        this.notifqueue.setSynchronous(notif[0] + 'Instant', 10);
                    }
                }

                if (notif[2] != undefined) {
                    this.notifqueue.setIgnoreNotificationCheck(notif[0], notif[2]);
                }
            });
        },

        // TODO: from this point and below, you can write your game notifications handling methods

        /*
        Example:

        notif_cardPlayed: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            console.log( notif );

            // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call

            // TODO: play the card in the user interface.
        },

        */
   });             
});
