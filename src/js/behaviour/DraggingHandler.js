import {Constants, Card} from './../main.js'
export class DraggingHandler{

    static current_Draged; 
    static current_DragHandler;
 
    static turn_defensiveCard_allowed = false;
    static turn_offensiveCard_allowed = false;

    static onOffensiveCardListener;
    static onDefensiveCardListener;

    static addCardDragListeners( card ){

        card.asHTML().draggable = true;

        function handleDragStart(event) {

            event.dataTransfer.setData('text/plain', ''); // Required for Firefox
            event.dataTransfer.setDragImage(event.target, 10, 10); // Optional: customize the drag image
            
            // here we check if a card is a offensive card, is the offensive card allowed to be played at the moment`?
            // like so with defensive 
            if(card.asHTML().classList.contains(Card.OffensiveCardClass)){
                //console.log("OFFENSIVE ALLOWED ? " + DraggingHandler.turn_offensiveCard_allowed)
                if(! DraggingHandler.turn_offensiveCard_allowed ){
                    return;
                }
            }else if(card.asHTML().classList.contains(Card.DefensiveCardClass)){
                //console.log("OFFENSIVE ALLOWED ? " + DraggingHandler.turn_defensiveCard_allowed)
                if(! DraggingHandler.turn_defensiveCard_allowed ){
                    return;
                }
            }else{
                // if the card is neither defensive or offensive, you are not allowed to drag it. 
                return;
            }

            DraggingHandler.current_Draged =  card;
            DraggingHandler.current_DragHandler = handleDragStart;
        }
    
        card.asHTML().addEventListener('dragstart', handleDragStart); 
    }

    static addDockingDragListeners( Dock ){

        // Drag Events Listeners DRAG OVER 
        Dock.asHTML().addEventListener('dragover', (event) => {
            event.preventDefault(); // Required to allow a drop
        });
            
        // Drag Event Listeners Fror DROP
        Dock.asHTML().addEventListener('drop', (event) => {
            
            if(DraggingHandler.current_Draged == null)
                return; 
                
            if(Dock.isOccupied()){
                return;
            }

            event.preventDefault();
            let a = DraggingHandler.current_Draged;
            a.dockAt( Dock ); 
            DraggingHandler.RemoveDragListeners(a); 

            // todo . this is present both in drag and in dock, find more efficient way of checking.
            if(DraggingHandler.current_Draged.asHTML().classList.contains(Card.OffensiveCardClass)){
                if( DraggingHandler.onOffensiveCardListener != null ){
                    DraggingHandler.onOffensiveCardListener.call(DraggingHandler.current_Draged );
                }
            }else if(DraggingHandler.current_Draged.asHTML().classList.contains(Card.DefensiveCardClass)){
                if( DraggingHandler.onDefensiveCardListener != null ){
                    DraggingHandler.onDefensiveCardListener.call(DraggingHandler.current_Draged );
                }
            } 
            DraggingHandler.current_Draged = null; 
        });
    } 

    static RemoveDragListeners(card){
        card.asHTML().removeEventListener('dragstart', DraggingHandler.current_DragHandler );
        card.asHTML().addEventListener('dragstart', (e) => {
            e.preventDefault(); 
        });
    }

    static enableTurn( onOffensiveCardListener  , onDefensiveCardListener  ){
        
        // set permisions to allowed, 
        DraggingHandler.turn_defensiveCard_allowed = true;
        DraggingHandler.turn_offensiveCard_allowed = true; 

        // listen for when the permisions are used up
        DraggingHandler.onOffensiveCardListener = {
            call( card ){
                DraggingHandler.turn_offensiveCard_allowed = false;
                onOffensiveCardListener( card );
            }
        }

        DraggingHandler.onDefensiveCardListener = {
            call( card ){
                DraggingHandler.turn_defensiveCard_allowed = false;
                onDefensiveCardListener( card );
            }
        }  
    }
}