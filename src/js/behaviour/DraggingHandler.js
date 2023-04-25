import {Constants} from './../main.js'
export class DraggingHandler{

    static current_Draged; 

    static addCardDragListeners( Card ){
        Card.asHTML().draggable = true;
        Card.asHTML().addEventListener('dragstart', (event) => {
            event.dataTransfer.setData('text/plain', ''); // Required for Firefox
            event.dataTransfer.setDragImage(event.target, 10, 10); // Optional: customize the drag image
            event.dataTransfer.setData('card', Card ); // Pass the CardModel object 
            DraggingHandler.current_Draged =  Card;
        });  
    }

    static addDockingDragListeners( Dock ){

        // Drag Events Listeners DRAG OVER
        Dock.asHTML().addEventListener('dragover', (event) => {
            event.preventDefault(); // Required to allow a drop
        });
            
        // Drag Event Listeners Fror DROP
        Dock.asHTML().addEventListener('drop', (event) => {
            event.preventDefault();
            let a = DraggingHandler.current_Draged;
            a.dockAt(Dock.asHTML()); 
        });
    } 
}