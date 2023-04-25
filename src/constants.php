<?php
    const CARDS_PER_ROW = 10;
    // Functionality Classes
    const DRAG_CLASS = "dragableObject";
    const GRAP_CLASS = "grabableObject";
    const DOCK_CLASS = "dockableObject";

    // Css Styling Classes

    // layers are levels of HTML that stack atop eachother
    const COMMON_LAYER_CLASS         ="Layer";
    const LAYER_TOPMessages          ="LayerTop";
    const LAYER_UI_Messages          ="LayerMsg";
    const LAYER_VFX_CLASS            ="LayerVFX";
    const LAYER_BOARD_CLASS          ="LayerBoard";  
    // Board segments
    const BOARD_CLASS                = "Board";
    const BOARD_SIDE_CLASS           = "BoardSide";
    const BOARD_Deck                 = "BoardDeck";
    //
    const CARDS_DECK_TABLE_CLASS     = "DeckTable";
    const CARDS_HANDDECK_TABLE_CLASS = "DeckHandTable";
    //
    // just the cards
    const CARD_CLASS                 = "BasicCard";
    const CARDS_HAND_CLASS           = "HandDeckCard";
    const CARDS_DEFE_CLASS           = "DefensiveDeckCard";
    const CARDS_OFFE_CLASS           = "OffensiveDeckCard";
 
    function CreateJavaScriptConstants(){
        return '
        <script> 
        const CARDS_PER_ROW             =  '.CARDS_PER_ROW.';
        // Functionality Classes
        const DRAG_CLASS                = "'.DRAG_CLASS.'";
        const GRAP_CLASS                = "'.GRAP_CLASS.'";
        const DOCK_CLASS                = "'.DOCK_CLASS.'";
        // Css Styling Classes
        // layers are levels of HTML that stack atop eachother
        const COMMON_LAYER_CLASS         ="'.COMMON_LAYER_CLASS         .'";
        const LAYER_TOPMessages          ="'.LAYER_TOPMessages          .'";
        const LAYER_UI_Messages          ="'.LAYER_UI_Messages          .'";
        const LAYER_VFX_CLASS            ="'.LAYER_VFX_CLASS            .'";
        const LAYER_BOARD_CLASS          ="'.LAYER_BOARD_CLASS          .'";
        // Board segments
        const BOARD_CLASS                = "'.BOARD_CLASS                .'";
        const BOARD_SIDE_CLASS           = "'.BOARD_SIDE_CLASS           .'";
        const BOARD_Deck                 = "'.BOARD_Deck                 .'";
        const CARDS_DECK_TABLE_CLASS     = "'.CARDS_DECK_TABLE_CLASS     .'";
        const CARDS_HANDDECK_TABLE_CLASS = "'.CARDS_HANDDECK_TABLE_CLASS .'";
        // just the cards
        const CARD_CLASS                 = "'.CARD_CLASS                 .'";
        const CARDS_HAND_CLASS           = "'.CARDS_HAND_CLASS           .'";
        const CARDS_DEFE_CLASS           = "'.CARDS_DEFE_CLASS           .'";
        const CARDS_OFFE_CLASS           = "'.CARDS_OFFE_CLASS           .'";
        </script>
        ';
    }
?>