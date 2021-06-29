{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- fixtheteleporter implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------
-->

<div id="board">
    <div id="player_board">
    </div>
</div>

<script type="text/javascript">
    var jstpl_tile = `<div id="tile" class="tile" data-type="\${type}" data-position="\${position}"></div>`;
    var jstpl_rotate = `<div id="rotate" class="rotate" data-mirror="\${mirror}" data-position="\${position}"><svg id="rotate_svg" height="52" viewBox="0 0 24 24" width="52" xmlns="http://www.w3.org/2000/svg"><polyline fill="none" points="23 4 23 10 17 10" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg></div>`;
</script>  

{OVERALL_GAME_FOOTER}
