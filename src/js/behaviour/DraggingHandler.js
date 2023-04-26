import {Constants} from './../main.js'
export class DraggingHandler{

    static current_Draged; 
    static current_DragHandler;

    static addCardDragListeners( Card ){

        Card.asHTML().draggable = true;

        function handleDragStart(event) {
            event.dataTransfer.setData('text/plain', ''); // Required for Firefox
            event.dataTransfer.setDragImage(event.target, 10, 10); // Optional: customize the drag image
            event.dataTransfer.setData('card', Card ); // Pass the CardModel object 
            DraggingHandler.current_Draged =  Card;
            DraggingHandler.current_DragHandler = handleDragStart;
        }
    
        Card.asHTML().addEventListener('dragstart', handleDragStart); 

        /*
        Card.asHTML().draggable = true;
        Card.asHTML().addEventListener('dragstart', (event) => {
            event.dataTransfer.setData('text/plain', ''); // Required for Firefox
            event.dataTransfer.setDragImage(event.target, 10, 10); // Optional: customize the drag image
            event.dataTransfer.setData('card', Card ); // Pass the CardModel object 
            DraggingHandler.current_Draged =  Card;
        });  */
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
            a.dockAt(Dock.asHTML()); 
            DraggingHandler.RemoveDragListeners(a);
            DraggingHandler.current_Draged = null;
             
        });
    } 

    static RemoveDragListeners(card){
        card.asHTML().removeEventListener('dragstart', DraggingHandler.current_DragHandler );
    }
}