<?php 
     class Card{

        public $target;
        public $damage; 
        public $rounds;
        public $cardType;
        public $cardScale;

        function toJSON(){

            return ( 
                "{
                    target:{". $this->target       ."},   
                    damage:{". $this->damage       ."},
                    rounds:{". $this->rounds       ."},
                    type:{  ". $this->cardType     ."},
                    scale:{ ". $this->cardScale    ."}
                }"
            );
        }

        static function fromJSON($json): Card{
            $data = json_decode($json, true);
            return new Card(
                $data['target'],
                $data['damage'],
                $data['rounds'],
                $data['type'],
                $data['scale']
            );
        }

        function __construct($target, $damage, $rounds, $CardType, $CardScale) {
            $this->target = $target; 
            $this->damage = $damage;
            $this->rounds = $rounds;
            $this->cardType = $CardType;
            $this->cardScale = $CardScale;
        }
 
    }  
    class Dictionary {

        private $internalDictionary;
    
        function __construct() {
            $this->internalDictionary = array();
        }
    
        function add($key, $value) :void {
            $this->internalDictionary[$key] = $value;
        }
    
        function remove($key) :void {
            unset($this->internalDictionary[$key]);
        }
    
        function contains($key) : bool{
            return array_key_exists($key, $this->internalDictionary);
        }

        public function getKeys() : array {
            $keys = array();
            foreach ($this->internalDictionary as $key => $value) {
                array_push($keys, $key);
            }
            return $keys;
        }
    } 
    class AlgorithmResponse{
        public GameState $state; 
        public float $value; 

        function __construct(Gamestate $state = null){
            if($state != null){
                $this->state = $state;
                $this->value = $state->eval();
            }else{
                $this->state = null;
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

        static function minimax(GameState $state, int $depth): AlgorithmResponse { 
            
            // if it has reached the max depth allowed then return that value
            if ($depth == 0 || $state->isGameOver()) {
                return new AlgorithmResponse($state);
            } 

            // if the active player is the player the ai is calculating for;
            // wich it is if the id is 0, see in the constructor, where the ai player has id = 0 assigned;
            $bestState = new AlgorithmResponse();
            if ($state->activePlayer->id == 0) {
                // we asume anything will be larger than minus infinity and thus will always get a response
                $bestState->value = -INF;  // minus infinity
                foreach ( AlgorithmSolver::generateNextStates($state) as $nextState) {
                    $result = AlgorithmSolver::minimax($nextState, $depth - 1); 
                    if($bestState->value >=  $result->value){
                        $bestState = $result;
                    } 
                }  
            } else { 
                // we assume that anything must be lower than infinity and thus we will always get a result
                $bestState->value = INF;  // minus infinity
                foreach ( AlgorithmSolver::generateNextStates($state) as $nextState) {
                    $result = AlgorithmSolver::minimax($nextState, $depth - 1); 
                    if($bestState->value <= $result->value){
                        $bestState = $result;
                    } 
                }   
            }
            return $bestState;
        }

        static function evaluateState(GameState $state): float {
            return $state->eval();
        }
    
        static function generateNextStates(GameState $state): array {
            return $state->CreateAllPossibleGameStates();
        }
    
       
    }

    class GameState{
 
        public $activePlayer;
        public $players; 
  
        public $lastlyMovedCards = Array();

        function __construct($AiPlayer, $players){ 
            $player_count = 0;
            $AiPlayer->id = $player_count++;
            array_push($this->players, $AiPlayer);

            for ($i=0; $i < count($players) ; $i++) { 
                $players[$i] -> $player_count++;
                array_push($this->players, $players[$i]);
            }  
        } 
        
        function InitStateValue() : void { 
            
            $offenseDict = new Dictionary();
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
 
        public $activePlayerId = 0; 
        public function SelectNextPlayer() : void {
            // 1%2 = 1 |2%2 = 0 |3%2 = 1 |4%2 = 0 |5%2 = 1 | 6%2 = 0 | 
            $this->activePlayerId = ($this->activePlayerId + 1) % count( $this->players );
            $this->activePlayer = $this->players[$this->activePlayerId];
        }

        function CreateAllPossibleGameStates( ): array{
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
                    
                    // push cards to the active lists. 
                    unset($lastlyMovedCards);
                    array_push( $lastlyMovedCards , $offensiveCard  );
                    array_push( $lastlyMovedCards , $defensiveCard  ); 
                    
                    array_push( $newState->activePlayer->offensiveCards , $offensiveCard  );
                    array_push( $newState->activePlayer->defensiveCards , $defensiveCard  ); 

                    $newState->SelectNextPlayer();
                    array_push($childNotes, $newState);
                }
            } 
            return $childNotes;
        }

        function isGameOver() : bool{
            return count($this->players)==1;
        } 
 
        public function eval() : float{
            $totalHealth = 0;
            $playerCount = count($this->players);
            foreach ($this->players as $player) {
                $totalHealth += $player->health; 
            }
            $meanHealth = $totalHealth / $playerCount;
        
            if ($this->activePlayer->health > $meanHealth) {
                return $this->activePlayer->health - $meanHealth;
            } else {
                return $this->activePlayer->health / $meanHealth;
            }
        }

    

    }

    class PlayerRoundDefense{
        
        public $def_Healing_rel;
        public $def_Healing_abs;

        public $def_Negation_rel;
        public $def_Negation_abs;

    }
    class PlayerRoundDamage{

        public $off_Damage_rel;
        public $off_Damage_abs;

        public $off_Bonus_rel;
        public $off_Bonus_abs;

    } 
    class Player{
        
        public $id;
        public $health;
        public $offensiveCards= Array();
        public $defensiveCards= Array(); 
        public $handCards_defensive = Array() ;
        public $handCards_offensive = Array() ;

        public $roundDefense;
        public $roundOffense;
 

        public static function createFromJSON($json) : Player{
            
            $THIS = new Player();
            $obj            = json_decode($json); 
            $THIS->health   = $obj->health;
 
            foreach ($THIS->offensiveCards as $cardJson) {
                array_push($THIS->offensiveCards, Card::fromJSON($cardJson) );
            }
             
            foreach ($THIS->defensiveCards as $cardJson) {
                array_push($THIS->defensiveCards, Card::fromJSON($cardJson) );
            }
  
            for ($i=0; $i < count($obj->handCards) ; $i++) { 
                $card = $obj->handCards[$i];
                if($card->cardType == CardType::DEFENSIVE){
                    // I turn it back into json for having acces to cards, from json mehtod, and to use it correctly
                    $THIS->handCards_defensive = Card::fromJSON(json_encode($card));
                }
                else
                if($card->cardType == CardType::OFFENSIVE){
                    // I turn it back into json for having acces to cards, from json mehtod, and to use it correctly
                    $THIS->handCards_offensive = Card::fromJSON(json_encode($card));
                }
            }
            return $THIS; 
        }

        static function create($id,$health,$offensiveCards,$defensiveCards,$handCards_defensive,$handCards_offensive) : Player{
            $THIS = new Player();
            $THIS->id                  = $id;
            $THIS->health              = $health;
            $THIS->offensiveCards      = $offensiveCards;
            $THIS->defensiveCards      = $defensiveCards; 
            $THIS->handCards_defensive = $handCards_defensive;
            $THIS->handCards_offensive = $handCards_offensive;    
            return $THIS;   
        } 

        private static function asPercent($total, $percent) : float {
            return ( $total / 100 ) * $percent;
        }

        function takeDamage(array $offenses): Player{ 

            $copy = $this->copy(); 
           
            // calc Healing, 
            $copy->health += $this->roundDefense->def_Healing_abs;
            $copy->health = Player::asPercent($copy->health , 1 + $this->roundDefense->def_Healing_rel);
            
            // calc damage
            $health_max = $copy->health;
            $health_cur = $copy->health;

            for ($i=0; $i < count($offenses) ; $i++) {  
                $o =  $offenses[$i];

                // calc raw damage.
                $absoluteDamage = Player::asPercent( $o->off_Damage_abs + $o->off_Bonus_abs , 1 + $o->off_Bonus_rel );
                $relativeDamage = Player::asPercent( $health_max, $o->off_Damage_rel + $o->off_Bonus_rel );

                // take defense into account
                $absoluteDamage = $absoluteDamage - $this->roundDefense->def_Negation_rel ;  
                $relativeDamage = $relativeDamage - $this->roundDefense->def_Negation_rel ; 

                $absoluteDamage = Player::asPercent($absoluteDamage, 1 -  $this->roundDefense->def_Negation_abs );  
                $relativeDamage = Player::asPercent($relativeDamage, 1 -  $this->roundDefense->def_Negation_abs );  

                // Take Damage!! WAHRRR! 
                $health_cur    = ($health_cur) - $relativeDamage - $absoluteDamage;
                $copy->health = $health_cur;
            }  

            // count down card rounds
            for ($i=0; $i < count($copy->defensiveCards) ; $i++) {  
                $card = $copy->defensiveCards[$i];
                $card->round -= -1;
                if($card->round == 0){
                    unset($copy->defensiveCards[$i]);
                } 
            }

            for ($i=0; $i < count($copy->roundDefense) ; $i++) {  
                $card = $copy->roundDefense[$i];
                $card->round -= -1;
                if($card->round == 0){
                    unset($copy->roundDefense[$i]);
                } 
            }
            return $copy;     
        }

        function calcRound(): void {
            $this->calc_defense();
            $this->calc_offense();
        }

        private function copy() : Player {
            $copy = new Player();
            $copy->id = $this->id;
            $copy->health = $this->health;
             
            $copy->offensiveCards = array();
            foreach ($this->offensiveCards as $card) {
                array_push($copy->offensiveCards, $card->copy());
            }
            
            $copy->defensiveCards = array();
            foreach ($this->defensiveCards as $card) {
                array_push($copy->defensiveCards, $card->copy());
            }
  
            return $copy;
        }
 
        function calc_defense() : void {
            
            // round defense;
            $this->roundDefense = new PlayerRoundDefense();

            // Every card needs to be added. 
            for ($i=0; $i < count($this->defensiveCards) ; $i++) {  
                $card = $this->defensiveCards[$i];
                switch($card->target){
                    case Target::SELF:
                        // interpreted as Healing for Self, thats why a defensive target is self.
                        $this->addValueAndConsiderScaling($card,$this->roundDefense->def_Healing_rel, $this->roundDefense->def_Healing_abs);
                        break;
                    case Target::ENEMY:
                        // interpreted as Damage Negation for Enemy Damage thats why a defensive target is self.
                        $this->addValueAndConsiderScaling($card,$this->roundDefense->def_Negation_rel, $this->roundDefense->def_Negation_abs);
                        break;
                } 
            }   
        }
 
        function calc_offense() : void { 
            
            // round offense ;
            $this->roundDefense = new PlayerRoundDamage();

            // Every card needs to be added. 
            for ($i=0; $i < count($this->offensiveCards) ; $i++) {  
                $card = $this->offensiveCards[$i];
                switch($card->target){
                    case Target::SELF:
                        // interpreted as Healing for Self, thats why a defensive target is self.
                        $this->addValueAndConsiderScaling($card,$this->roundDefense->off_Bonus_rel, $this->roundDefense->off_Bonus_abs);
                        break;
                    case Target::ENEMY:
                        // interpreted as Damage Negation for Enemy Damage thats why a defensive target is self.
                        $this->addValueAndConsiderScaling($card,$this->roundDefense->off_Damage_rel, $this->roundDefense->off_Damage_abs);
                        break;
                } 
            }   
        }
        
        function addValueAndConsiderScaling(&$card,&$relative, &$absolute):void{
            switch($card->cardScale){
                case CardScale::RELATIVE:
                    $relative = $relative + $card->damage;
                    break;
                case CardScale::ABSOLUTE:
                    $absolute = $absolute + $card->damage;
                    break;
            }
        } 
  
    }
  

    // Parse input from request body
    $requestBody = file_get_contents('php://input');
    $input = json_decode($requestBody, true);

    // Call the appropriate endpoint based on the request path
    $path = explode('/', $_SERVER['REQUEST_URI']);
    $lastPath = $path [ count($path) -1 ];
    switch ( $lastPath ) {
        case 'calcAIMove': 
            echo "SUCCES!! ";
            break;
        default:
            echo  $lastPath."NAHH dont worry be happy, duh duh duh dodo dodo dodododod";
            exit();
    }
 
    header('Content-Type: application/json');
    echo json_encode($output); 
?>  
<pre>
<?php

    $arr = [12,12,12,12,12];
    
    print_r($_COOKIE);


?>
</pre>