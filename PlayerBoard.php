 

 <?php
    
    function generatePlayerAndBoard($playerId, $slots) {
    $ContainerClass = "PlayerContainer";
    $healthBar      = "PlayerHealthBar";
    $InnerHealthBar = "InnerHealthBar";
    $CardRow        = "CardRow";
    $OffCardRow     = "OffensiveCardRow";
    $DefCardRow     = "DefensiveCardRow";
    $HandRow        = "HandRow";
 ?>

<div class="<?php echo $ContainerClass ?>" id="<?php  echo $playerId ?>">
    <div class="<?php echo $healthBar ?>"><div class="<?php echo $InnerHealthBar ?>"></div></div>
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
    let healthBar   = element.querySelector(".PlayerHealthBar");
    let OffRow      = element.querySelector(".OffensiveCardRow");
    let DefRow      = element.querySelector(".DefensiveCardRow");
    let handRow     = element.querySelector(".HandRow");

    let playerBoard = PlayerBoard.CreatePlayerBoard(element,healthBar,OffRow,DefRow,handRow,<?php echo $slots ?>);
    game.registerPlayer(playerBoard);


</script>
<?php
    }
?>