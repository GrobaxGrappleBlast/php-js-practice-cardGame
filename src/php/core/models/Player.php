<?php 
require_once 'src/php/core/core.php';

class Player{
        
        public $id;
        public $health;
        public $offensiveCards= Array();
        public $defensiveCards= Array(); 
        public $handCards_defensive = Array() ;
        public $handCards_offensive = Array() ;
  

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

        public function takeDamage( PlayerRoundDamage $offense ): Player{ 
           
            $copy = clone $this; 
            $defense = $copy->calc_defense();

            // calc Healing, 
            $copy->health += $defense->def_Healing_abs;
            $copy->health = Player::asPercent($copy->health , 1 + $defense->def_Healing_rel);
            
            // calc damage
            $health_max = $copy->health;
            $health_cur = $copy->health;

            
            $o =  $offense;

            // calc raw damage. 
            $absoluteDamage = Player::asPercent( $o->off_Damage_abs + $o->off_Bonus_abs , 1 + $o->off_Bonus_rel );
            $relativeDamage = Player::asPercent( $health_max, $o->off_Damage_rel + $o->off_Bonus_rel );

            // take defense into account
            $absoluteDamage = $absoluteDamage - $defense->def_Negation_rel ;  
            $relativeDamage = $relativeDamage - $defense->def_Negation_rel ; 

            $absoluteDamage = Player::asPercent($absoluteDamage, 1 -  $defense->def_Negation_abs );  
            $relativeDamage = Player::asPercent($relativeDamage, 1 -  $defense->def_Negation_abs );  

            // Take Damage!! WAHRRR! 
            $health_cur    = ($health_cur) - $relativeDamage - $absoluteDamage;
            $copy->health = $health_cur;

            // count down card rounds : DEFENSIVE
            for ($i=0; $i < count($copy->defensiveCards) ; $i++) {  
                $card = $copy->defensiveCards[$i]; 
                $card->rounds -= 1;
                if($card->rounds == 0){
                    unset($copy->defensiveCards[$i]);
                } 
            }

            // count down card rounds : OFENSIVE
            for ($i=0; $i < count($copy->offensiveCards) ; $i++) {  
                $card = $copy->offensiveCards[$i];
                $card->rounds -= 1;
                if($card->rounds == 0){
                    unset($copy->offensiveCards[$i]);
                } 
            } 
            return $copy;     
        }
 
        public function calc_defense() : PlayerRoundDefense {
            // round defense;
            $roundDefense = new PlayerRoundDefense(); 
            // Every card needs to be added. 
            for ($i=0; $i < count($this->defensiveCards) ; $i++) {  
                $card = $this->defensiveCards[$i];
                switch($card->target){
                    case Target::SELF:
                        // interpreted as Healing for Self, thats why a defensive target is self.
                        $this->addValueAndConsiderScaling($card,$roundDefense->def_Healing_rel, $roundDefense->def_Healing_abs);
                        break;
                    case Target::ENEMY:
                        // interpreted as Damage Negation for Enemy Damage thats why a defensive target is self.
                        $this->addValueAndConsiderScaling($card,$roundDefense->def_Negation_rel, $roundDefense->def_Negation_abs);
                        break;
                } 
            }   
            return $roundDefense; 
        }
 
        public function calc_offense() : PlayerRoundDamage {
            // round offense ;
            $roundDefense = new PlayerRoundDamage(); 
            // Every card needs to be added. 
            for ($i=0; $i < count($this->offensiveCards) ; $i++) {  
                $card = $this->offensiveCards[$i];
                switch($card->target){
                    case Target::SELF:
                        // interpreted as Healing for Self, thats why a defensive target is self.
                        $this->addValueAndConsiderScaling($card,$roundDefense->off_Bonus_rel, $roundDefense->off_Bonus_abs);
                        break;
                    case Target::ENEMY:
                        // interpreted as Damage Negation for Enemy Damage thats why a defensive target is self.
                        $this->addValueAndConsiderScaling($card,$roundDefense->off_Damage_rel, $roundDefense->off_Damage_abs);
                        break;
                } 
            }
            return $roundDefense;  
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

        private static function asPercent($total, $percent) : float {
            return ( $total / 100 ) * $percent;
        }

        public function copy(): Player {
            $copy = clone $this; 
            $copy->offensiveCards       = array_map(function($card) { return clone $card; }, $this->offensiveCards        );
            $copy->defensiveCards       = array_map(function($card) { return clone $card; }, $this->defensiveCards        );
            $copy->handCards_offensive  = array_map(function($card) { return clone $card; }, $this->handCards_offensive   );
            $copy->handCards_defensive  = array_map(function($card) { return clone $card; }, $this->handCards_defensive   );
            return $copy;
        }

        public function playCards(Card $defensiveCard,Card $offensiveCard ){

            $this->handCards_offensive = array_values(array_filter($this->handCards_offensive, 
                function ($card)  use ($offensiveCard){ 
                    return $card->hash() != $offensiveCard->hash();
                }
            )); 
            
            $this->handCards_defensive = array_values(array_filter($this->handCards_defensive, 
                function ($card)  use ($defensiveCard){ 
                    return $card->hash() != $defensiveCard->hash();
                }
            ));
 
            array_push($this->defensiveCards , $offensiveCard);
            array_push($this->offensiveCards , $defensiveCard); 
 
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