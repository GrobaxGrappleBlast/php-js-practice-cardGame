 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <?php
        // dependencies; 
        require_once 'src/php/core/constants.php'; 
        require_once 'src/php/htmlGen/PlayerBoard.php';    
    ?>
     
     
    <?php

        function input_sanitization($input){
            $out = $input; 
            $out = strip_tags($out); 
            $out = htmlentities($out); 
            $length = mb_strlen($out, 'UTF-8');
            $out = mb_substr($out, 0, 25, 'UTF-8');
            return $out;
        }
 
        function number_interprate($number, $max , $min ){
            if($number < 0)
                $number = $number * -1;

            if($number < $min)
                $number = $min;

            if($number > $max)
                $number =  $max;
            
            return $number;
        }

        $GameRunning = false; 
        if(isset($_POST["Reset"])) { 
            $GameRunning = false; 
        }   

        if(isset($_POST["NewGame"])) {
            $GameRunning = true;    
            $player1 = input_sanitization($_POST['player1']);
            $player2 = input_sanitization($_POST['AI']);
            $rounds         = number_interprate(intval($_POST['Rounds']), 10, 1);
            $generalHealth  = number_interprate(intval($_POST['health']), 1000, 100);

            if($player1 == $player2){
                $player2 = $player2 . "2";
            } 
        } 
    ?> 
     <script type="module">
        import { Game } from './src/js/main.js'; 
        Game.getFirstInstance(<?=$generalHealth?>);
    </script>
</head>
<body>
    <?php
    function createStartForm(){
        return '
        <form method="post" action="" class="InputForm" >
            
            <label for="player1">player 1</label>
            <input type="text" id="player1" name="player1" value="player1"><br>

            <label for="AI">AI</label>
            <input type="text" id="AI" name="AI" value="AI"><br>

            <label for="rounds">Max Rounds "n"</label>
            <input type="number" id="Rounds" name="Rounds" value="8" min="1" max="10" ><br>
            
            <label for="health">start Health "m"</label>
            <input type="number" id="health" name="health" value="300" min="10" max="1000" ><br>

            <input type="submit" name="NewGame" value="Start Game">

        </form>
        ';
    } 
    function createResetButton(){
        return '
        <form method="post" action="">
            <input type="submit" name="Reset" value="Reset">
        </form>
        ';
    }
    function createPlayerBoard($playerid, $slots, $playerName){
        return ' '. generatePlayerAndBoard($playerid, $slots, $playerName, false) .' ';
    }
    function createPlayerAIBoard($playerid, $slots, $playerName){
        return ' '. generatePlayerAndBoard($playerid, $slots, $playerName, true) .' ';
    }

    echo ' <div id="'.LAYER_BOARD_CLASS         .'" class="'.COMMON_LAYER_CLASS.'"> ';
    if($GameRunning){
        echo createPlayerBoard(     "Player1", $rounds, $player1 );
        echo createPlayerAIBoard(   "Player2", $rounds, $player2 );  
    }
    echo '</div>
    <div id="'.LAYER_TOPMessages         .'" class="'.COMMON_LAYER_CLASS.'">'. (!$GameRunning ?   createStartForm() : createResetButton()) .'</div>
    '; 
    ?>

    <script type="module">
        import { Game } from './src/js/main.js'; 
        let game = Game.getInstance();
        game.start(<?= $rounds ?>); 
    </script>
</body>
</html>