<?php 
  
    require_once 'src/php/core/core.php';

    $JSON = '{"player":{"health":300,"offensive_row":[{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""}],"defensive_row":[{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""}],"handCards":[{"target":0,"damage":10,"rounds":4,"cardType":0,"cardScale":0},{"target":1,"damage":26,"rounds":3,"cardType":0,"cardScale":0},{"target":1,"damage":11,"rounds":5,"cardType":0,"cardScale":0},{"target":0,"damage":6,"rounds":5,"cardType":0,"cardScale":1},{"target":1,"damage":22,"rounds":3,"cardType":0,"cardScale":0},{"target":1,"damage":5,"rounds":2,"cardType":0,"cardScale":1},{"target":1,"damage":11,"rounds":2,"cardType":0,"cardScale":1},{"target":1,"damage":24,"rounds":4,"cardType":0,"cardScale":1},{"target":1,"damage":7,"rounds":4,"cardType":1,"cardScale":1},{"target":0,"damage":20,"rounds":2,"cardType":1,"cardScale":0},{"target":1,"damage":30,"rounds":4,"cardType":1,"cardScale":0},{"target":0,"damage":16,"rounds":2,"cardType":1,"cardScale":0},{"target":1,"damage":5,"rounds":1,"cardType":1,"cardScale":1},{"target":1,"damage":21,"rounds":2,"cardType":1,"cardScale":1},{"target":1,"damage":5,"rounds":4,"cardType":1,"cardScale":0},{"target":0,"damage":11,"rounds":4,"cardType":1,"cardScale":1}]},"oponents":[{"name":"player1","board":{"health":300,"offensive_row":[{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":{"target":0,"damage":29,"rounds":2,"cardType":0,"cardScale":0}},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""}],"defensive_row":[{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":{"target":0,"damage":7,"rounds":3,"cardType":1,"cardScale":1}},{"type":"dock","card":""},{"type":"dock","card":""}],"handCards":[{"target":0,"damage":28,"rounds":1,"cardType":0,"cardScale":1},{"target":0,"damage":8,"rounds":1,"cardType":0,"cardScale":1},{"target":1,"damage":19,"rounds":2,"cardType":0,"cardScale":1},{"target":1,"damage":28,"rounds":5,"cardType":0,"cardScale":0},{"target":0,"damage":12,"rounds":3,"cardType":0,"cardScale":0},{"target":0,"damage":29,"rounds":2,"cardType":0,"cardScale":0},{"target":1,"damage":30,"rounds":2,"cardType":0,"cardScale":0},{"target":0,"damage":9,"rounds":4,"cardType":0,"cardScale":1},{"target":1,"damage":27,"rounds":1,"cardType":1,"cardScale":1},{"target":1,"damage":16,"rounds":5,"cardType":1,"cardScale":0},{"target":0,"damage":28,"rounds":5,"cardType":1,"cardScale":1},{"target":0,"damage":7,"rounds":3,"cardType":1,"cardScale":1},{"target":0,"damage":14,"rounds":5,"cardType":1,"cardScale":0},{"target":1,"damage":7,"rounds":1,"cardType":1,"cardScale":0},{"target":1,"damage":23,"rounds":2,"cardType":1,"cardScale":1},{"target":0,"damage":29,"rounds":1,"cardType":1,"cardScale":1}]}}]}';

    // Parse input from request body
    $requestBody = file_get_contents('php://input');
    $DTO = json_decode($JSON, true);

?>  
<pre>
<?php 
      
    $oponents = Array();
    for ($i=0; $i < count($DTO["oponents"]) ; $i++) { 
        array_push( $oponents ,  Player::createFromJSON( $DTO["oponents"][0]["board"] ));
    }  
    $AIPlayer = Player::createFromJSON( $DTO["player"] );  
    $currentState = GameState::CreateFirstGameState( $AIPlayer , $oponents );
    $nextState = AlgorithmSolver::minimax( $currentState , 3); 

    print_r($nextState->state->activePlayer);
?>
</pre>