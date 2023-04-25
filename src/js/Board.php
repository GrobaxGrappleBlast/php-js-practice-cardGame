<?php
    
    require_once 'src/constants.php';

    class Side {  
        public static function getEmptySideHTML( $isFlipped = false ){
            $rtr = '<table class="'.CARDS_DECK_TABLE_CLASS.'">';
            $methods_to_call = array('getAttackRow', 'getDefenseRow');
            if($isFlipped){
                for ($i=0; $i < count($methods_to_call); $i++) { 
                    $method = $methods_to_call[$i];
                    $rtr .= self::$method();
                }
            }
            else{
                for ($i= count($methods_to_call) -1 ; $i >= 0 ; $i--) { 
                    $method = $methods_to_call[$i];
                    $rtr .= self::$method();  
                }
            }
            $rtr .= '</table>';
            $rtr .= '
                    <div class="'.CARDS_HANDDECK_TABLE_CLASS.'"> 
                    '. self::getHandDeckRow() .'
                    </div>';
            return $rtr;
        } 
        // Board Docks
        private static function getAttackRow(){
            $rtr = "<tr>"; 
            for ( $i=0 ; $i < CARDS_PER_ROW ; $i++ ) { 
                $rtr .= self::createDock( CARDS_OFFE_CLASS );
            } 
            $rtr .= "</tr>";
            return $rtr;
        }
        private static function getDefenseRow(){
            $rtr = "<tr>";
            for ( $i=0 ; $i < CARDS_PER_ROW ; $i++ ) { 
                $rtr .= self::createDock( CARDS_DEFE_CLASS );
            } 
            $rtr .= "</tr>";
            return $rtr;
        }
        private static function createDock( $extraClass ){
            return "
                <td class='" . DOCK_CLASS . " " . $extraClass . " " . CARD_CLASS . "'>
                </td>
            ";
        } 


        private static function getHandDeckRow(){
            $rtr = "";
            for ( $i=0 ; $i < CARDS_PER_ROW ; $i++ ) { 
                $rtr .= self::createCard( CARDS_HAND_CLASS );
            } 
            return $rtr;
        }
        private static function createCard($extraClass){
            return "
            <div class='" . $extraClass . " " . CARD_CLASS . "'>
            </div>
            ";
        } 
    }

    class Deck{

        // Todo Hook up to a database and retrieve Cards. 
        // OR hook up to a Data layer. and retrieve from there.

        public static function CreateDeck(){
            return "
            <div class='". BOARD_Deck ." " . CARD_CLASS . "'>
            </div>";
        }
    }


    class Board{
        // todo do something with the numplayers 
        public static function CreateBoard( $numplayers ){
            return "
            <div class='". BOARD_CLASS ."'>
                ".Deck::CreateDeck()."
                <div class='".BOARD_SIDE_CLASS."'>". Side::getEmptySideHTML(false) ."</div>
                <div class='".BOARD_SIDE_CLASS."'>". Side::getEmptySideHTML(true) ."</div>
            </div>";
        }
    }


?>