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
            this._notifications = [
                ['flipTile'],
                ['changeTiles'],
                ['newScore'],
                ['newCard'],
            ];
        },
        
        setup(gamedatas)
        {
            console.log( "Starting game setup" );
            
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                if (player_id === this.gamedatas.gamestate.active_player) {
                    player.hand.forEach((type, position) => {
                        dojo.place(this.format_block('jstpl_flip', {mirror: [1,2].includes(position), position: position}), 'player_board');
                        dojo.place(this.format_block('jstpl_tile', {type: type, position: position}), 'player_board');
                    });
                }
            }
            this.displayNewCard(gamedatas.card);
            dojo.query('.flip').forEach((flipButton) => {
                dojo.connect(flipButton, 'onclick', (evt) =>  {
                    evt.preventDefault();
                    evt.stopPropagation();
                    this.onClickFlip(flipButton.dataset.position)
                });
            })
            dojo.query('.tile').forEach((tile) => {
                this.connectToAction(tile);
            });

            this.setupNotifications();
            console.log( "Ending game setup" );
        },
       

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState(stateName, args)
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
        onLeavingState(stateName)
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
        onUpdateActionButtons(stateName, args)
        {
            console.log( 'onUpdateActionButtons: '+stateName );
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
                 case 'playerTurn':
                     this.addActionButton('buttonEndTurn', _('I\'m done!'), 'onClickFinished', null, false, 'blue');
                    break;
                }
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        onClickFlip(position) {
            this.takeAction('actFlip', {
                position: position,
            });
        },

        onClickSelect(position) {
            let selected = dojo.query('.selected');
            const tileAtPosition = dojo.query(`.tile[data-position='${position}']`)[0]
            if (selected.length === 0) {
                dojo.addClass(tileAtPosition, 'selected');
            } else {
                selected = selected[0];
                if (selected === tileAtPosition) {
                    dojo.removeClass(tileAtPosition, 'selected');
                } else {
                    this.takeAction('actChange', {
                        positions: `${position};${selected.dataset.position}`,
                    });
                }
            }
        },

        onClickFinished() {
            this.takeAction('actDone', {});
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

        connectToAction(tile) {
            dojo.connect(tile, 'onclick', (evt) =>  {
                evt.preventDefault();
                evt.stopPropagation();
                this.onClickSelect(tile.dataset.position)
            });
        },

        displayNewCard(types) {
            dojo.query('.card_container.tile').forEach((tile) => {
                dojo.destroy(tile);
            })
            types.forEach((type, i) => {
                dojo.place(this.format_block('jstpl_tile', {type: type, position: i}), 'card_container');
            });
        },

        ///////////////////////////////////////////////////
        //// Notifications methods
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

        notif_flipTile(n) {
            const tile = dojo.query(`.tile[data-type='${n.args.from}']`)[0];
            const position = tile.dataset.position;
            dojo.destroy(tile);
            const newTile = dojo.place(this.format_block('jstpl_tile', {type: n.args.to, position: position}), 'player_board');
            this.connectToAction(newTile);
        },

        notif_changeTiles(n) {
            const positions = n.args.positions;
            const tiles = positions.map((position) => {
                return dojo.query(`.tile[data-position='${position}']`)[0];
            });
            tiles.forEach((tile) => {
                const oldPosition = parseInt(tile.dataset.position);
                const newPosition = positions.find((position) => position !== oldPosition);
                dojo.attr(tile, 'data-position', newPosition);
            });
            const selected = dojo.query('.selected')[0];
            dojo.removeClass(selected, 'selected');
        },

        notif_newScore(n) {
            const playerId = n.args.player_id;
            this.scoreCtrl[playerId].toValue(this.scoreCtrl[playerId].current_value + 1);
        },

        notif_newCard(n) {
            this.displayNewCard(n.args.card)
        },
   });             
});
