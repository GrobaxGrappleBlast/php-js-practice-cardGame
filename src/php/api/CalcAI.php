<?php
    require_once 'src/php/core/core.php';
    
     class moveFormat{
        public $move = Array();
        function __construct(GameState $state){
            foreach ( $state->lastlyMovedCards as $card) {
                array_push($this->move, $card->uniqueId );
            }
        }
    }
?>

<?php 
   
    $JSON = '{"player":{"name":"","health":300,"board":{"offensive_row":[{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""}],"defensive_row":[{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""}],"handCards":[{"uniqueId":"o8","target":1,"damage":5,"rounds":4,"cardType":0,"cardScale":0},{"uniqueId":"o9","target":0,"damage":8,"rounds":2,"cardType":0,"cardScale":0},{"uniqueId":"o10","target":0,"damage":11,"rounds":5,"cardType":0,"cardScale":1},{"uniqueId":"o11","target":0,"damage":19,"rounds":5,"cardType":0,"cardScale":0},{"uniqueId":"o12","target":0,"damage":15,"rounds":3,"cardType":0,"cardScale":1},{"uniqueId":"o13","target":0,"damage":2,"rounds":3,"cardType":0,"cardScale":0},{"uniqueId":"o14","target":0,"damage":3,"rounds":1,"cardType":0,"cardScale":0},{"uniqueId":"o15","target":1,"damage":17,"rounds":1,"cardType":0,"cardScale":0},{"uniqueId":"d8","target":1,"damage":25,"rounds":5,"cardType":1,"cardScale":1},{"uniqueId":"d9","target":1,"damage":11,"rounds":5,"cardType":1,"cardScale":0},{"uniqueId":"d10","target":0,"damage":15,"rounds":4,"cardType":1,"cardScale":0},{"uniqueId":"d11","target":0,"damage":28,"rounds":5,"cardType":1,"cardScale":1},{"uniqueId":"d12","target":0,"damage":12,"rounds":5,"cardType":1,"cardScale":1},{"uniqueId":"d13","target":1,"damage":23,"rounds":5,"cardType":1,"cardScale":0},{"uniqueId":"d14","target":0,"damage":14,"rounds":1,"cardType":1,"cardScale":0},{"uniqueId":"d15","target":0,"damage":26,"rounds":4,"cardType":1,"cardScale":1}]}},"oponents":[{"name":"player1","health":300,"board":{"offensive_row":[{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":{"uniqueId":"o5","target":0,"damage":9,"rounds":4,"cardType":0,"cardScale":1}},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""}],"defensive_row":[{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":{"uniqueId":"d2","target":1,"damage":30,"rounds":2,"cardType":1,"cardScale":0}},{"type":"dock","card":""},{"type":"dock","card":""},{"type":"dock","card":""}],"handCards":[{"uniqueId":"o0","target":1,"damage":7,"rounds":3,"cardType":0,"cardScale":0},{"uniqueId":"o1","target":0,"damage":3,"rounds":5,"cardType":0,"cardScale":0},{"uniqueId":"o2","target":0,"damage":8,"rounds":3,"cardType":0,"cardScale":0},{"uniqueId":"o3","target":1,"damage":2,"rounds":1,"cardType":0,"cardScale":1},{"uniqueId":"o4","target":1,"damage":14,"rounds":4,"cardType":0,"cardScale":1},{"uniqueId":"o5","target":0,"damage":9,"rounds":4,"cardType":0,"cardScale":1},{"uniqueId":"o6","target":0,"damage":23,"rounds":2,"cardType":0,"cardScale":1},{"uniqueId":"o7","target":1,"damage":18,"rounds":2,"cardType":0,"cardScale":1},{"uniqueId":"d0","target":1,"damage":18,"rounds":4,"cardType":1,"cardScale":0},{"uniqueId":"d1","target":0,"damage":25,"rounds":4,"cardType":1,"cardScale":0},{"uniqueId":"d2","target":1,"damage":30,"rounds":2,"cardType":1,"cardScale":0},{"uniqueId":"d3","target":0,"damage":13,"rounds":2,"cardType":1,"cardScale":1},{"uniqueId":"d4","target":0,"damage":2,"rounds":2,"cardType":1,"cardScale":1},{"uniqueId":"d5","target":0,"damage":6,"rounds":5,"cardType":1,"cardScale":0},{"uniqueId":"d6","target":0,"damage":17,"rounds":4,"cardType":1,"cardScale":0},{"uniqueId":"d7","target":1,"damage":20,"rounds":1,"cardType":1,"cardScale":1}]}}]}';

    // Parse input from request body
    $requestBody = file_get_contents('php://input');
    if($requestBody == null){
        $DTO = json_decode($JSON, true);
    }else{
        $DTO = json_decode($requestBody, true);
    }

    $oponents = Array();
    for ($i=0; $i < count($DTO["oponents"]) ; $i++) { 
        array_push( $oponents ,  Player::createFromJSON( $DTO["oponents"][0] ));
    }  


    $AIPlayer = Player::createFromJSON( $DTO["player"] );  
    $currentState = GameState::CreateFirstGameState( $AIPlayer , $oponents );
    $nextState = AlgorithmSolver::minimax( $currentState , 2); 

    print_r(json_encode(new moveFormat($nextState->state)));
?>
 