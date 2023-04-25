
import {Constants, DraggingHandler} from './main.js'

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

    toJSON(){
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
    dock  ;
    model ;
    view  ; 
    constructor(json = null){
        
        if(json == null){
            this.model = new CardModel();
        }else{
            this.model = fromJSON(json);
        }
        
        this.view = new CardView();
        this.view.render(this.model);
   
        DraggingHandler.addCardDragListeners(this);
    }

    static fromJSON(json){
        let card = new Card();
        card.model = CardModel.fromJSON(json);
        return card;
    } 

    toJSON(){
        this.model.toJSON();
    }
    
    dockAt(dock){
        this.dock = dock ;
        this.dock.appendChild(this.view.element);
    }

    asHTML(){
        return this.view.element;
     }

}

 