 
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
    
    
    <script>

        var game_has_started = false;
    <?php

        function input_sanitization($input){
            $out = $input; 
            $out = strip_tags($out); 
            $out = htmlentities($out); 
            $length = mb_strlen($out, 'UTF-8');
            $out = mb_substr($out, 0, 25, 'UTF-8');
            return $out;
        }
 
        $GameRunning = false; 
        if(isset($_POST["Reset"])) { 
            $GameRunning = false; 
        }  

        if(isset($_POST["NewGame"])) {
            $GameRunning = true;    
            $player1 = input_sanitization($_POST['player1']);
            $player2 = input_sanitization($_POST['AI']);
            
            if($player1 == $player2){
                $player2 = $player2 . "2";
            }
        
        } 
    ?>
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
        echo createPlayerBoard("Player1",8, $player1);
        echo createPlayerAIBoard("Player2",8, $player2);  
    }
    echo '</div>
    <div id="'.LAYER_TOPMessages         .'" class="'.COMMON_LAYER_CLASS.'">'. (!$GameRunning ?   createStartForm() : createResetButton()) .'</div>
    '; 
    ?>

    <script type="module">
        import { Game } from './src/js/main.js';

        let game = Game.getInstance();
        game.start(25);

    </script>
</body>
</html>