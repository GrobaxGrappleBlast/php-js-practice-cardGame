import { Card , Dock , Constants} from './main.js'

/*class Side {  
    public static function getEmptySideHTML( $isFlipped = false ){
        $rtr = '<table class="'.CARDS_DECK_TABLE_CLASS.'">';
        $methods_to_call = array('getAttackRow', 'getDefenseRow');
        if($isFlipped){
            for ($i=0; $i < count($methods_to_call); $i++) { 
                $method = $methods_to_call[$i];
                $rtr .= self::$method();
            }
        }
        else{
            for ($i= count($methods_to_call) -1 ; $i >= 0 ; $i--) { 
                $method = $methods_to_call[$i];
                $rtr .= self::$method();  
            }
        }
        $rtr .= '</table>';
        $rtr .= '
                <div class="'.CARDS_HANDDECK_TABLE_CLASS.'"> 
                '. self::getHandDeckRow() .'
                </div>';
        return $rtr;
    } 
    // Board Docks
    private static function getAttackRow(){
        $rtr = "<tr>"; 
        for ( $i=0 ; $i < CARDS_PER_ROW ; $i++ ) { 
            $rtr .= self::createDock( CARDS_OFFE_CLASS );
        } 
        $rtr .= "</tr>";
        return $rtr;
    }
    private static function getDefenseRow(){
        $rtr = "<tr>";
        for ( $i=0 ; $i < CARDS_PER_ROW ; $i++ ) { 
            $rtr .= self::createDock( CARDS_DEFE_CLASS );
        } 
        $rtr .= "</tr>";
        return $rtr;
    }
    private static function createDock( $extraClass ){
        return "
            <td class='" . DOCK_CLASS . " " . $extraClass . " " . CARD_CLASS . "'>
            </td>
        ";
    } 


    private static function getHandDeckRow(){
        $rtr = "";
        for ( $i=0 ; $i < CARDS_PER_ROW ; $i++ ) { 
            $rtr .= self::createCard( CARDS_HAND_CLASS );
        } 
        return $rtr;
    }
    private static function createCard($extraClass){
        return "
        <div class='" . $extraClass . " " . CARD_CLASS . "'>
        </div>
        ";
    } 
}*/

class BoardSide_model{
    
    offensive_row;
    defensive_row;
    handCards;

    constructor( slots ){
        this.offensive_row = new Array(slots);
        this.defensive_row = new Array(slots);
        this.handCards = [];
    }

    static fromJSON(json, slots=8){
        let rtr = new BoardSide_model(slots);
        let obj = JSON.parse(json == null ? "{}" : json);
      
        for (let i = 0; i < rtr.offensive_row.length; i++) {
          if (obj.offensive_row !== undefined && obj.offensive_row[i] !== undefined) {
            rtr.offensive_row[i] = Dock.fromJSON(JSON.stringify(obj.offensive_row[i]));
          } else {
            rtr.offensive_row[i] = new Dock();
          }
        }
      
        for (let i = 0; i < rtr.defensive_row.length; i++) {
          if (obj.defensive_row !== undefined && obj.defensive_row[i] !== undefined) {
            rtr.defensive_row[i] = Dock.fromJSON(JSON.stringify(obj.defensive_row[i]));
          } else {
            rtr.defensive_row[i] = new Dock();
          }
        }
      
        for (let i = 0; i < rtr.handCards.length; i++) {
            if (obj.handCards !== undefined && obj.handCards[i] !== undefined) {
                rtr.handCards.push( 
                    Dock.fromJSON(JSON.stringify(obj.handCards[i])) 
                );
            }
        }
        
        return rtr;
    }

    addToHand(Card){
        this.handCards.push(Card);
    }
}
class BoardSide_View{

    container;
    board ;
    tbody   ;    
    offensive_row;
    defensive_row; 
    hand ;

    constructor( parent ){
         
        // Create elements
        this.container  = document.createElement("div");
        this.hand       = document.createElement("div");
        this.board      = document.createElement("table");
        // create elements for the board;
        this.tbody = document.createElement("tbody");
        this.offensive_row = document.createElement("tr");
        this.defensive_row = document.createElement("tr");

        // give elements classes,
        this.board.classList.add(Constants.CARDS_DECK_TABLE_CLASS); 
        this.offensive_row.classList.add("offensiveRow"); 
        this.defensive_row.classList.add("defensiveRow"); 
        this.hand.classList.add(Constants.CARDS_HANDDECK_TABLE_CLASS);

        // append Elements
        this.container.appendChild(this.board);
        this.board.appendChild(this.tbody);
        this.tbody.appendChild(this.offensive_row)
        this.tbody.appendChild(this.defensive_row)
        this.container.appendChild(this.hand);
         
        if(parent != null)
            parent.appendChild(this.container)
    }

    render(model){
        console.log("rendering BoardSide")

        this.offensive_row.innerHTML="";
        this.defensive_row.innerHTML="";

        model.offensive_row.forEach( dock => {
            dock.attachToParent(this.offensive_row);
        });

        model.defensive_row.forEach( dock =>{
            dock.attachToParent(this.defensive_row);
        }) 
    } 

    revealHand(){
        console.error("revealHand NOT implemented");
    }
    hideHand(){
        console.error("hideHand NOT implemented")
    }

    addToHand(card){
        card.dockAt(this.hand);
    }

    dragFromHand(element){
        console.error("dragFromHand NOT implemented")
    }
}

export class BoardSide{

    model;
    view ;

    constructor(  layer , slots, json = null){
        if(json ==null){
            this.model = BoardSide_model.fromJSON(json);
        }else{
            this.model = new BoardSide_model(slots);
        }
        this.view = new BoardSide_View(layer);
    } 

    render(){
        this.view.render(this.model);
    }  
    revealHand(){
        this.view.revealHand();
    }
    hideHand(){
        this.view.hideHand();
    } 
    addToHand(card){
        this.view.addToHand(card); 
    }
}