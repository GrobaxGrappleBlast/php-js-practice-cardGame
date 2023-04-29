
import {Constants, DraggingHandler, Dock} from './main.js'

class CardModel{  
    
    uniqueId;
    target;
    damage; 
    rounds;
    cardType;
    cardScale;

    static fromJSON(json){ 
 
        let obj         = JSON.parse(json);
        let card        = new CardModel();
        card.uniqueId   = obj.uniqueId  ;
        card.target     = obj.target    ;
        card.damage     = obj.damage    ; 
        card.rounds     = obj.rounds    ;
        card.cardType   = obj.cardType  ;
        card.cardScale  = obj.cardScale ;
        return card;
    }

}
class CardView{
    element = document.createElement("div");

    constructor(){
        // add Class 
        this.element.classList.add(Constants.CARD_CLASS); 
    }

    render(model){ 
        if(model.cardType == 0)
            this.element.classList.add(Card.OffensiveCardClass);
        else 
            this.element.classList.add(Card.DefensiveCardClass);
 
        this.element.innerHTML = `
            ${model.target      },
            ${model.damage      },
            ${model.rounds      },
            ${model.cardType    },
            ${model.cardScale   },
        `; 
    }
}
 
export class Card { 
    
    static OffensiveCardClass = "OFFENSIVE_CARD";
    static DefensiveCardClass = "DEFENSIVE_CARD";

    dock  ;
    model ;
    view  ; 
    
    constructor(json = null){
        
        if(json == null){
            this.model = new CardModel();
        }else{
            this.model = CardModel.fromJSON(json);
        }
        
        this.view = new CardView();
        this.view.render(this.model);
   
        DraggingHandler.addCardDragListeners(this);
    }

    static fromJSON(json){ 
        let card = new Card(json); 
        return card;
    } 

    getDTO(){
        return this.model;
    }
    
    dockAt(dock){
        this.dock = dock ;
        console.log("DOCKING CARD")
        if(this.dock instanceof Dock){
            console.log("OCCUPY DOCK CALLED ");
            this.dock.occupyDock(this);
        }
        else{
            this.dock.appendChild(this.view.element);
        }
    }

    asHTML(){
        return this.view.element;
    }



}

 