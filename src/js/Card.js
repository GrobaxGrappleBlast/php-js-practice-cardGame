
import {Constants} from './main.js'

class CardModel{  
    type;
    value;
    command; 
    static fromJSON(json){ 
        let obj         = JSON.parse(json);
        let card        = new CardModel();
        card.type       = obj.type;
        card.value      = obj.value;
        card.command    = obj.command
        return card;
    }

    toJson(){
        return `
        {
            "type"      : "`+this.type+`"     ,
            "value"     : "`+this.value+`"    ,
            "command"   : "`+this.command+`"  
        }
        `
    }
}

class CardView{
    element = document.createElement("div");

    constructor(){
        // add Class 
        this.element.classList.add(Constants.CARD_CLASS); 
    }

    render(model){
        this.element.innerHTML = `
            <div class="Card_header" >`+ model.value   || "UNDEFINED" +`<div>
            <div class="Card_effect" >`+ model.command || "UNDEFINED" +`<div>
        `; 
    }
}

export class Card {

    model ;
    view  ;

    constructor(json = null){
        if(json == null){
            this.model = new CardModel();
        }else{
            this.model = fromJSON(json);
        }
        
        this.view = new CardView();
        this.view.render(this.model)


        // Dragging 
        this.view.element.draggable = true;
        this.view.element.addEventListener('dragstart', (event) => {
            console.log("DRAG");
            event.dataTransfer.setData('text/plain', ''); // Required for Firefox
            event.dataTransfer.setDragImage(event.target, 10, 10); // Optional: customize the drag image
            event.dataTransfer.setData('card', this.model.toJson() ); // Pass the CardModel object
            // Any additional code you want to execute when dragging starts
        }); 
    }

    static fromJSON(json){
        let card = new Card();
        card.model = CardModel.fromJSON(json);
        return card;
    }
 
}

 