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
        /*/ TODO FINISH THIS CODE AND TEST.     
        document.addEventListener("dragover", function(event) {
			event.preventDefault();
		});

        // -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
        // Draging  -- Draging  -- Draging  -- Draging  --
        // -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
        let dragged = null ;
        // when the event starts, take the element, remove it from its parent, and add to free elements on the screen
        // then ensure that it follows the mouse cursor around. 
        document.addEventListener("dragstart", function(event) {
            // if the class drag is in the elements classes;
            if (event.target.classList.contains( <?php echo GRAP_CLASS ?> ) ) {
                dragged = event.target;
			    event.target.classList.add(<?php echo DRAG_CLASS ?>);
			}
		});

        // when the dragging ends, ensure that either the Drop happens, OR return to parent, and. 
		document.addEventListener("dragend", function(event) {
            // if the class drag is in the elements classes;
            if (event.target.classList.contains( <?php echo GRAP_CLASS ?> ) ) {
                dragged = event.target;
			    event.target.classList.add(<?php echo DRAG_CLASS ?>);
			} 
		});

        // -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
        // Docking  -- Docking  -- Docking  -- Docking  -- 
        // -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
		document.addEventListener("drop", function(event) {
			event.preventDefault();
			if (event.target.className == <?php echo DOCK_CLASS ?>) {
				event.target.style.border = "solid 1px black";
				event.target.appendChild(dragged);
			}
		});
        */
    </script>
     
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
        <form method="post" action="">
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
    <div id="'.LAYER_VFX_CLASS           .'" class="'.COMMON_LAYER_CLASS.'"></div>
    <div id="'.LAYER_UI_Messages         .'" class="'.COMMON_LAYER_CLASS.'"></div>
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