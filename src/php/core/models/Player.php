<?php 
require_once 'src/php/core/core.php';

class Player{
        
        public $id;
        public $health;
        public $offensiveCards= Array();
        public $defensiveCards= Array(); 
        public $handCards_defensive = Array() ;
        public $handCards_offensive = Array() ;

        public $roundDefense;
        public $roundOffense;
 

        public static function createFromJSON($json){
            $THIS = new Player();
            $THIS->health   = $json["health"];
             
            foreach ($json["offensive_row"] as $cardJson) {
                if ($cardJson["card"] != null) {
                    array_push($THIS->offensiveCards, Card::fromJSON($cardJson["card"]));
                }
            }
            
            foreach ($json["defensive_row"] as $cardJson) {
                if ($cardJson["card"] != null) {
                    array_push($THIS->defensiveCards, Card::fromJSON($cardJson["card"]));
                }
            }

            foreach ($json["handCards"] as $cardJson) {  
                $card = Card::fromJSON($cardJson);  
                if( $card->cardType == CardType::DEFENSIVE){
                    // I turn it back into json for having acces to cards, from json mehtod, and to use it correctly
                    array_push($THIS->handCards_defensive , $card );
                }
                else
                if( $card->cardType == CardType::OFFENSIVE){
                    // I turn it back into json for having acces to cards, from json mehtod, and to use it correctly
                    array_push($THIS->handCards_offensive , $card );
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

        public function takeDamage(array $offenses): Player{ 

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

        public function calcRound(): void {
            $this->calc_defense();
            $this->calc_offense(); 
        }
 
        private function calc_defense() : void {
            
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
 
        private function calc_offense() : void { 
            
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
        
        private function addValueAndConsiderScaling(&$card,&$relative, &$absolute):void{
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
?>