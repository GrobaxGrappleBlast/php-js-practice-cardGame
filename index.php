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
        
        // Writing the constants as Javascript constants
        CreateJavaScriptConstants();
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
            <input type="text" id="player1" name="player1"><br>
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
    echo '
    <div id="'.LAYER_BOARD_CLASS         .'" class="'.COMMON_LAYER_CLASS.'"></div> 
    <div id="'.LAYER_TOPMessages         .'" class="'.COMMON_LAYER_CLASS.'">'. (!$GameRunning ?   createStartForm() : createResetButton()) .'</div>
    '; 
    ?>

        <script type="module" >
            import { Game } from '/src/js/Game.js';

            if(game_has_started){
                var boardClass = '<?php echo LAYER_BOARD_CLASS ?>';
                var game = new Game( boardClass);
                game.start(2 , 8);
            }

        </script>
</body>
</html>