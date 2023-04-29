<?php
require_once 'src/php/core/core.php';

class GameState{
 
    public $activePlayerId = 0; 
    public $activePlayer;
    public $players = Array(); 

    public $lastlyMovedCards = Array();
    function __construct(){}

    /**
    * Creates a first Gamestate, where it sets the values of the players' id Member, Wich
    * is important in the last state evaluation.
    */
    public static function CreateFirstGameState(Player $activePlayer , Array $players){
  
        $THIS = new GameState();
        $activePlayer->id = 0;
        array_push($THIS->players, $activePlayer);
         
        for ($i=0; $i < count($players) ; $i++) { 
            $players[$i]->id = ( 1 + $i );
            array_push($THIS->players, $players[$i]);
        }  
        
        $THIS->activePlayer = $activePlayer;  
        return $THIS;
    }
 
    /**
    * cycles through active players, this should be called when creating states, because the next state would
    * Have the next active player.
    */
    public function rotatePlayer() : void {
        // 1%2 = 1 |2%2 = 0 |3%2 = 1 |4%2 = 0 |5%2 = 1 | 6%2 = 0 | 
        $this->activePlayerId = ($this->activePlayerId + 1) % count( $this->players );
        $this->activePlayer = $this->players[$this->activePlayerId];
    }

    /**
    * calculates offense, and applies it to all other players than the active player
    */
    public function AttackOponents() : void {  
        // calculate offense;
        $offense = $this->activePlayer->calc_offense();

        // apply offense , all oponents  
        for ($i=0; $i < count($this->players) ; $i++) {  
            $this->players[$i]->takeDamage($offense);
        } 
    }
     
    /**
    * Returns a bool value if the game is over, the game is over when there is only one player left
    * n number of cards, k picked cards ( will always be 2 )
    * C(n,k) = n! / (k! * (n-k)!) number of combinations, so this escalates quickly // todo find way to lessen this.
    * 8! / (2! * (8-2)!) = 28  
    *
    * @return array of GameStates
    */
    public function CreateAllPossibleGameStates( ): array{
        
        $childNotes = array();  
    
        // Generate all possible combinations of offensive and defensive cards
        $offensiveCards = $this->activePlayer->handCards_offensive;
        $defensiveCards = $this->activePlayer->handCards_defensive;
        for ($o=0; $o < count($offensiveCards) ; $o++) { 
            for ($d=0; $d < count($defensiveCards) ; $d++) {  
    
                print_r( count($offensiveCards) . " - " . $o . "::".count($defensiveCards) . " - " . $d . "\n" );
                $offensiveCard = $offensiveCards[$o];
                $defensiveCard = $defensiveCards[$d];
    
                // Create a new game state for each combination of cards.
                $newState = $this->copy(); 
     
                $newState->activePlayer->playCards( $defensiveCard, $offensiveCard ); 
                 
                // push cards to the active lists. 
                unset($this->lastlyMovedCards); 
                $this->lastlyMovedCards = Array();
    
                array_push( $this->lastlyMovedCards , $offensiveCard  );
                array_push( $this->lastlyMovedCards , $defensiveCard  ); 
                 
                array_push($childNotes, $newState);
                echo "." ;
            }
        }  
        // update the gamestates
        foreach ( $childNotes as &$note ) {
            $note->AttackOponents();
            $note->rotatePlayer();
        } 
    
        return $childNotes;
    }


    public function copy() : GameState {
 
        $copy = clone $this;
        $copy->players = array_map(function($player) { return $player->copy(); }, $this->players );
        foreach ($copy->players as $player) { 
            if ($player->id == $copy->activePlayerId) {
                $copy->activePlayer = $player;
            }
        }   
        return $copy;
    }

    
    private function arrayTostring(array $arr){
        $rtr = "[";
        foreach( $arr as $a ){
            $rtr .=  "\n" . $a->__toString(); 
        }
        $rtr .= "]";
        return $rtr;
    }

    /**
     * Returns a bool value if the game is over, the game is over when there is only one player left
     */
    public function isGameOver() : bool{  
        return count($this->players) <= 1;
    } 

    /**
     * Returns a value representing a float value of how good this state is for the active player
     * The number is the mean of all oponents vs the active players health
     * activeplayerhealth / meanOpponenthealth;
     */
    public function eval() : float{
        $totalHealth = 0;
        $playerCount = count($this->players) -1;
        foreach ($this->players as $player) {
            if($this->activePlayer->id == $player->id)
                continue;
            $totalHealth += $player->health; 
        } 
        $meanHealth = $totalHealth / $playerCount;
        return $this->activePlayer->health / $meanHealth; 
    }
} 
?>