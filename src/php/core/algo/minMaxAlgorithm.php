<?php
require_once 'src/php/core/core.php';
    
    class AlgorithmResponse{
        public GameState $state; 
        public float $value; 

        function __construct(Gamestate $state = null){
            if($state != null){
                $this->state = $state;
                $this->value = $state->eval();
            }else{ 
                $this->value = 0;
            }
        }
    } 
    
    class AlgorithmSolver{  
 
        // this is a minMax algorithm. What it is suppose to do is simple.
        // it evaluates possible gamestates. assumes that it will take the Best out come for it self ( MAX POINTS VALUE)
        // and it assumes that the enemy will take the best action for the enemy (THE MINIMUM POINT VALUE) 
        // it is a recursive algorithm. and it will call it self over and over to create a tree node structure. 
        // where all leafs are nodes of possible gamestates. 

        static function minimax(GameState $state, int $depth, bool $isFirst = true ): AlgorithmResponse { 
            
           
            
            if ($depth == 0 || $state->isGameOver()) {  
                return new AlgorithmResponse($state);
            } 

            // if the active player is the player the ai is calculating for;
            // wich it is if the id is 0, see in the constructor, where the ai player has id = 0 assigned;
            $bestState = new AlgorithmResponse();
            $bestState->value = -INF;  // minus infinity
            $bestNextState = null; 
 
            foreach (  AlgorithmSolver::generateNextStates($state, $isFirst) as $nextState ) {
                $result = AlgorithmSolver::minimax($nextState, $depth - 1, false); 
                if ($bestState->value <= $result->value) {
                    $bestState = $result;
                    $bestState->state = $nextState; // update the best state with the next state that led to the best outcome
                    $bestNextState = $nextState; // update the best next state
                } 
            }

            $response = new AlgorithmResponse();
            $response->state = $bestNextState;
            $response->value = $bestState->value;
            return $response;
        }

        static function evaluateState(GameState $state): float {
            return $state->eval();
        }
    
        static function generateNextStates(GameState $state,  bool $isFirst): array {
            return $state->CreateAllPossibleGameStates($isFirst);
        }
    
       
    }

?>
