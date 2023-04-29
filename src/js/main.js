import { Card } from './Card.js';
import { Dock } from './Dock.js';
import {PlayerBoard} from './PlayerBoard.js';  
import {Game} from './Game.js';  
import {DraggingHandler} from './behaviour/DraggingHandler.js'
import {Player, HumanPlayer,AIPlayer} from './player.js';
import {generalMethods} from './behaviour/general.js'
import {apiCaller} from './apiCaller/apiCaller.js'

const Constants = { 
    // Functionality Classes
    DRAG_CLASS : "dragableObject",
    GRAP_CLASS : "grabableObject",
    DOCK_CLASS : "dockableObject",

    // Css Styling Classes

    // layers are levels of HTML that stack atop eachother
    COMMON_LAYER_CLASS         :"Layer",
    LAYER_TOPMessages          :"LayerTop",
    LAYER_UI_Messages          :"LayerMsg",
    LAYER_VFX_CLASS            :"LayerVFX",
    LAYER_BOARD_CLASS          :"LayerBoard",  
    // Board segments
    BOARD_CLASS                : "Board",
    BOARD_SIDE_CLASS           : "BoardSide",
    BOARD_Deck                 : "BoardDeck",
    //
    CARDS_DECK_TABLE_CLASS     : "DeckTable",
    CARDS_HANDDECK_TABLE_CLASS : "DeckHandTable",
    //
    // just the cards
    CARD_CLASS                 : "BasicCard",
    CARDS_HAND_CLASS           : "HandDeckCard",
    CARDS_DEFE_CLASS           : "DefensiveDeckCard",
    CARDS_OFFE_CLASS           : "OffensiveDeckCard",
}


export {AIPlayer,HumanPlayer,Game, Card, Dock, PlayerBoard, Constants ,DraggingHandler,Player, generalMethods, apiCaller};