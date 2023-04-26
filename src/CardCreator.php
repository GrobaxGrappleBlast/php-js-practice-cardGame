<?php

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

    class CardEffect{

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

        function __construct($target, $damage, $rounds, $CardType, $CardScale) {
            $this->target = $target; 
            $this->damage = $damage;
            $this->rounds = $rounds;
            $this->cardType = $CardType;
            $this->cardScale = $CardScale;
        }
    }
 

    // Define a function that returns some data
    function getOffensive($count) {

        $cards = array();

        for ($i = 0; $i < $count; $i++) {
            $Card = new CardEffect(
                rand(0, 1)  ,   // Target Self or Enemy
                rand(1, 30) ,   // Damage Number,
                rand(1, 5)  ,   // Rounds it works
                CardType::OFFENSIVE  ,   // Type
                rand(0, 1)  ,   // Scaling Type
            );

            array_push($cards, $Card);
        }

        return $cards;
    }

    function getDefensive($count) {
        $cards = array();

        for ($i = 0; $i < $count; $i++) {
            $Card = new CardEffect(
                rand(0, 1)  ,   // Target Self or Enemy
                rand(1, 30) ,   // Damage Number,
                rand(1, 5)  ,   // Rounds it works
                CardType::DEFENSIVE  ,   // Type
                rand(0, 1)  ,   // Scaling Type
            );
    
            array_push($cards, $Card);
        }
    
        return $cards;
    }
    
    switch ($_GET["request"]) {
        case "offensive":
            // Handle the "data" request by returning some data
            $count = isset($_GET['count']) ? intval($_GET['count']) : 1;
            echo json_encode(getOffensive($count));
            break;
        case "defensive":
            $count = isset($_GET['count']) ? intval($_GET['count']) : 1;
            echo json_encode(getDefensive($count));
            break;
        default:
            // Handle any other requests with a 404 error
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
            break;
    }
   
       
?>


