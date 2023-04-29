<?php
require_once 'src/php/core/core.php';
 
    class Target {
        const SELF = 0;
        const ENEMY = 1;
    }
 
    class CardType{
        const OFFENSIVE = 0;
        const DEFENSIVE = 1;
    }
    
    class CardScale{
        const RELATIVE = 0;
        const ABSOLUTE = 1;
    }

    /**
    * Represents a Game Card Object
    */
    class Card{

        /** self or enemy 
         * @var Target */
        public $target;
        /** raw number data
         *  @var int */
        public $damage; 
        /** int representing how many rounds more the card is alive 
        * @var int */
        public $rounds;
        /** raw number data, can be offensive or defensive 
         * @var CardType */
        public $cardType;
        /** raw number data,relative means percent and absolute is just raw number 
         * @var CardScale */
        public $cardScale;

        function __construct($target, $damage, $rounds, $CardType, $CardScale) {
            $this->target = $target; 
            $this->damage = $damage;
            $this->rounds = $rounds;
            $this->cardType = $CardType;
            $this->cardScale = $CardScale;
        }

        public function __toString(): string {
            $targetStr  = $this->target     == Target   ::SELF      ? "SELF"        : "ENEMY";
            $typeStr    = $this->cardType   == CardType ::OFFENSIVE ? "OFFENSIVE"   : "DEFENSIVE";
            $scaleStr   = $this->cardScale  == CardScale::RELATIVE  ? "RELATIVE"    : "ABSOLUTE";
            return "Target: $targetStr, Damage: $this->damage, Rounds: $this->rounds, Type: $typeStr, Scale: $scaleStr";
        }

        /**
        * Createas the Card from a json object;
        * @return Card 
        */
        static function fromJSON($json) : Card{   
            return new Card(
                $json['target'],
                $json['damage'],
                $json['rounds'],
                $json['cardType'],
                $json['cardScale']
            );
        }
    }

?>