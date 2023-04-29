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
    private function rotatePlayer() : void {
        // 1%2 = 1 |2%2 = 0 |3%2 = 1 |4%2 = 0 |5%2 = 1 | 6%2 = 0 | 
        $this->activePlayerId = ($this->activePlayerId + 1) % count( $this->players );
        $this->activePlayer = $this->players[$this->activePlayerId];
    }

    
    private function InitStateValue() : void { 
        
        $offenseDict = new Dictionary();
        // calculate Damage output for each player
        for ($i=0; $i < count($this->players) ; $i++) { 
            $player = &$this->players[$i];
            $player->calcRound();
            $offenseDict->add( $player->id, $player->roundOffense );
        }

        for ($i=0; $i < count($this->players) ; $i++) { 
            $player = &$this->players[$i];
            $player->calcRound();
            $offenseDict->add( $player->id, $player->roundOffense );
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
        foreach ($offensiveCards as $offensiveCard) {
            foreach ($defensiveCards as $defensiveCard) { 

                // Create a new game state for each combination of cards.
                $newState = clone $this;

                // Withdrawing the current cards, from active player. 
                $newState->activePlayer->handCards_offensive = array_diff($offensiveCards, [$offensiveCard]);
                $newState->activePlayer->handCards_defensive = array_diff($defensiveCards, [$defensiveCard]);
 
                // above out commented code implemented ass array filter instead of array diff due to something with strings;
                /*
                $newState->activePlayer->handCards_offensive = array_values(array_filter(
                    $offensiveCards,
                    function ($card) use ($offensiveCard) {
                        return $card->id !== $offensiveCard->id;
                    }
                ));
                $newState->activePlayer->handCards_defensive = array_values(array_filter(
                    $defensiveCards,
                    function ($card) use ($defensiveCard) {
                        return $card->id !== $defensiveCard->id;
                    }
                ));
                */

                // push cards to the active lists. 
                unset($this->lastlyMovedCards); 
                $this->lastlyMovedCards = Array();

                array_push( $this->lastlyMovedCards , $offensiveCard  );
                array_push( $this->lastlyMovedCards , $defensiveCard  ); 
                
                array_push( $newState->activePlayer->offensiveCards , $offensiveCard  );
                array_push( $newState->activePlayer->defensiveCards , $defensiveCard  ); 

                $newState->SelectNextPlayer();
                array_push($childNotes, $newState);
                echo "." ;
            }
        } 


        foreach ( $childNotes as $note ) {
            // todo 
            // eachGamestate Should have the active player attack using the selected offense card;

            // eachGameState Should Rotate the Player()
        } 
        return $childNotes;
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
        print_r( "\nEVAL (){\n\tplayerCount:".$playerCount."\n\ttotalHealth:".$totalHealth."\n\tMeanHealth:".$meanHealth."\n\tActivePlayerHealth:".$this->activePlayer->health."\n}"   );
        return $this->activePlayer->health / $meanHealth; 
    }
} 
?>