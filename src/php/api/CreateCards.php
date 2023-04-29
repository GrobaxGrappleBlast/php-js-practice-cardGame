<?php
    require_once 'src/php/core/core.php';
  
    // Define a function that returns some data
    function getOffensive($count) {

        $cards = array();

        for ($i = 0; $i < $count; $i++) {
            $Card = new Card(
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
            $Card = new Card(
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


