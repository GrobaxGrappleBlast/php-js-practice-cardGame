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
        require_once 'src/constants.php'; 
        require_once 'PlayerBoard.php';    
    ?>
    
    
    <script>

        var game_has_started = false;
    <?php

        $GameRunning = false;
        session_start();
        if(isset($_POST["Reset"])) {
            $_SESSION = array();
            $GameRunning = false;
            echo 'game_has_started = false;';
        }  

        if(isset($_POST["NewGame"])) {
            $GameRunning = true;
            echo 'game_has_started = true;';
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
          
            <label for="player2">player 2</label>
            <input type="text" id="player2" name="player2" value="player2"><br>
          
            <input type="submit" name="NewGame" value="Start Game">
        </form>';
    } 
    function createResetButton(){
        return '
        <form method="post" action="">
            <input type="submit" name="Reset" value="Reset">
        </form>
        ';
    }
    function createPlayerBoard($playerid, $slots){
        return ' '. generatePlayerAndBoard($playerid, $slots) .' ';
    }

    echo ' <div id="'.LAYER_BOARD_CLASS         .'" class="'.COMMON_LAYER_CLASS.'"> ';
    if($GameRunning){
        echo createPlayerBoard("Player1",8);
        echo createPlayerBoard("Player2",8);  
    }
    echo '</div>
    <div id="'.LAYER_TOPMessages         .'" class="'.COMMON_LAYER_CLASS.'">'. (!$GameRunning ?   createStartForm() : createResetButton()) .'</div>
    '; 
    ?>

    <script type="module">
        import { Game } from './src/js/main.js';

        let game = Game.getInstance();
        game.start();

    </script>
</body>
</html>