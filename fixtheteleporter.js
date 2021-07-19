/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Please Fix the Teleporter implementation : © Pavel Kulagin kuzwiz@mail.ru
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
  'dojo',
  'dojo/_base/declare',
  'ebg/core/gamegui',
  'ebg/counter',
  g_gamethemeurl + 'modules/js/DojoConnections.js',
  g_gamethemeurl + 'modules/js/Animations.js',
], function (dojo, declare) {
  return declare(
    'bgagame.fixtheteleporter',
    [ebg.core.gamegui, fixtheteleporter.dojoconnections, fixtheteleporter.animations],
    {
      constructor: function () {
        console.log('fixtheteleporter constructor');
        this._notifications = [['flipTile'], ['changeTiles'], ['playerClaimedFinish'], ['matchChecked', 5000]];
      },

      setup(gamedatas) {
        console.log('Starting game setup');
        // Setting up player boards
        dojo.attr('everything_else_area', 'data-players', Object.entries(gamedatas.players).length);
        Object.values(gamedatas.players_ordered).forEach((player, i) => {
          const current = player.id === this.player_id;
          const placeId = current ? 'current_player_area' : 'everything_else_area';
          const playerArea = dojo.place(
            this.format_block('jstpl_playerArea', {
              playerId: player.id,
              no: i,
            }),
            placeId,
          );
          if (!current) {
            dojo.place(
              this.format_block('jstpl_playerName', {
                playerName: player.name,
                color: player.color,
              }),
              playerArea,
            );
          }
          dojo.place(
            this.format_block('jstpl_playerBoard', {
              playerId: player.id,
              current: current,
            }),
            playerArea,
          );
          player.hand.forEach((type, position) => {
            if (i === 0) {
              dojo.place(
                this.format_block('jstpl_flip', {
                  mirror: [1, 2].includes(position),
                  position: position,
                }),
                `player_board_${player.id}`,
              );
            }
            dojo.place(
              this.format_block('jstpl_tile', {
                type: type,
                position: position,
              }),
              `player_board_${player.id}`,
            );
          });
        });
        this.displayNewCard(gamedatas.card);
        if (gamedatas.players[this.player_id].active) {
          this.dojoConnectAllFlipsAndTiles();
        }

        this.setupNotifications();
        console.log('Ending game setup');
      },

      ///////////////////////////////////////////////////
      //// Game & client states

      // onEnteringState: this method is called each time we are entering into a new game state.
      //                  You can use this method to perform some user interface changes at this moment.
      //
      onEnteringState(stateName, args) {
        console.log('Entering state: ' + stateName);
        switch (stateName) {
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
      onLeavingState(stateName) {
        console.log('Leaving state: ' + stateName);

        switch (stateName) {
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
      onUpdateActionButtons(stateName, args) {
        console.log('onUpdateActionButtons: ' + stateName);
        if (this.isCurrentPlayerActive()) {
          switch (stateName) {
            case 'playerTurn':
              this.addActionButton('buttonEndTurn', _("I'm done!"), 'onClickFinished', null, false, 'blue');
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
        const tileAtPosition = dojo.query(`.player_board[data-current='true'] .tile[data-position='${position}']`)[0];
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

      dojoConnectAllFlipsAndTiles() {
        dojo.query('.flip').forEach((flipButton) => {
          this.connect(flipButton, this.onClickFlip.bind(this), flipButton.dataset.position);
        });
        dojo.query('.player_board[data-current="true"] .tile').forEach((tile) => {
          this.connect(tile, this.onClickSelect.bind(this), tile.dataset.position, tile.dataset.position);
        });
        this.connect(dojo.query('#card')[0], this.onClickFinished.bind(this));
      },

      displayNewCard(types) {
        dojo.place(this.format_block('jstpl_card', {}), 'card_container');
        types.forEach((type, i) => {
          dojo.place(this.format_block('jstpl_tile', { type: type, position: i }), 'card');
        });
      },

      ///////////////////////////////////////////////////
      //// Notifications methods
      setupNotifications: function () {
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
        const tile = dojo.query(`#player_board_${n.args.player_id} .tile[data-type='${n.args.from}']`)[0];
        const position = tile.dataset.position;
        const currentPlayer = parseInt(n.args.player_id) === this.player_id;
        if (currentPlayer) {
          this.disconnect(position);
        }
        dojo.destroy(tile);
        const newTile = dojo.place(
          this.format_block('jstpl_tile', {
            type: n.args.to,
            position: position,
          }),
          `player_board_${n.args.player_id}`,
        );
        if (currentPlayer) {
          this.connect(newTile, this.onClickSelect.bind(this), newTile.dataset.position, newTile.dataset.position);
        }
      },

      notif_changeTiles(n) {
        const positions = n.args.positions;
        const destinationPlayerId = parseInt(n.args.player_id);
        const tiles = positions.map((position) => {
          return dojo.query(`#player_board_${destinationPlayerId} .tile[data-position='${position}']`)[0];
        });
        if (this.player_id === destinationPlayerId) {
          this.disconnect(positions);
        }
        tiles.forEach((tile) => {
          const oldPosition = parseInt(tile.dataset.position);
          const newPosition = positions.find((position) => position !== oldPosition);
          dojo.attr(tile, 'data-position', newPosition);
          if (this.player_id === destinationPlayerId) {
            this.connect(tile, this.onClickSelect.bind(this), newPosition, newPosition);
          }
        });
        this.removeAllClasses(['selected'], destinationPlayerId);
      },

      notif_playerClaimedFinish(n) {
        this.dojoDisconnectAllEvents();
        const color = this.gamedatas.players[n.args.player_id].color;
        dojo.attr('card', 'data-claimed-color', `#${color}`);
      },

      async notif_matchChecked(n) {
        const userMistakes = n.args.mistakes;
        const playerId = n.args.player_id;
        const order = [0, 1, 3, 2]; // To visually show it prettier
        for (let index = 0; index < order.length; index++) {
          const tile = dojo.query(`#player_board_${playerId} #tile[data-position="${order[index]}"]`)[0];
          let newClass = userMistakes[order[index]] ? 'checked-correct' : 'checked-incorrect';
          dojo.addClass(tile, newClass);
          await this.sleep(1000);
        }
        const duration = 700;
        const userFailed = userMistakes.includes(false);
        if (userFailed) {
          this.fadeOutAndDestroy('card', duration, 0);
        } else {
          await this.slide('card', `player_board_${playerId}`, {
            duration: duration,
            clearPos: false,
            destroy: true,
          });
          this.scoreCtrl[playerId].toValue(this.scoreCtrl[playerId].current_value + 1);
        }
        await this.sleep(duration);
        this.displayNewCard(n.args.newCard);
        this.removeAllClasses(['checked-correct', 'checked-incorrect']);
        if (!userFailed || playerId !== this.player_id) {
          this.dojoConnectAllFlipsAndTiles();
        }
      },

      removeAllClasses(classes, playerId = '') {
        classes.forEach((clazz) => {
          const playerBoardClass = playerId === '' ? '' : `#player_board_${playerId} `;
          const elements = dojo.query(`${playerBoardClass}.${clazz}`);
          elements.forEach((element) => {
            dojo.removeClass(element, clazz);
          });
        });
      },

      sleep(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
      },
    },
  );
});
