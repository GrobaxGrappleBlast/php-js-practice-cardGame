 <?php

    require_once 'src/php/core/constants.php';
    
    function generatePlayerAndBoard($playerId, $slots, $playerName, $isAI = false) {
    $ContainerClass = "PlayerContainer";
    $healthBar      = "PlayerHealthBar";
    $InnerHealthBar = "InnerHealthBar";
    $CardRow        = "CardRow";
    $OffCardRow     = "OffensiveCardRow";
    $DefCardRow     = "DefensiveCardRow";
    $HandRow        = "HandRow";
 ?>
<span>
    <div class="<?php echo $ContainerClass ?>" id="<?php  echo $playerId ?>">
            <div class="<?php echo $healthBar ?>"><div class="<?php echo $InnerHealthBar ?>"></div><span class="PlayerNameHeader"><h3><?= $playerName?></h3></span></div>
            <div class="<?php echo $CardRow ." ".$OffCardRow  ?>">
                
            </div>
            <div class="<?php echo $CardRow ." ".$DefCardRow  ?>">
                
            </div>
            <div class="<?php echo $CardRow ." ".$HandRow  ?>">
            
            </div>
    </div>
    <script type="module">
        
        import { PlayerBoard, Game } from './src/js/main.js'

        let game        = Game.getInstance();
        let element     = document.getElementById("<?php echo $playerId ?>");
        let healthBar       = element.querySelector(".PlayerHealthBar");
        let InnerhealthBar  = element.querySelector(".InnerHealthBar");
        let OffRow      = element.querySelector(".OffensiveCardRow");
        let DefRow      = element.querySelector(".DefensiveCardRow");
        let handRow     = element.querySelector(".HandRow");

        InnerhealthBar.style.width = '100%';

        let playerBoard = PlayerBoard.CreatePlayerBoard(element,InnerhealthBar,OffRow,DefRow,handRow,<?php echo $slots ?>);
        <?php 
            if($isAI){
                ?>
                    game.registerAIPlayerBoard(playerBoard, "<?=$playerName?>");
                <?php
            }else{
                ?>
                    game.registerPlayerBoard(playerBoard, "<?=$playerName?>");
                <?php
            } 
        ?> 
    </script>
</span>
<?php
    }
?>