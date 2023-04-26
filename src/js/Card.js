
import {Constants, DraggingHandler} from './main.js'

class CardModel{  
    
    target;
    damage; 
    rounds;
    cardType;
    cardScale;

    static fromJSON(json){ 
 
        let obj         = JSON.parse(json);
        let card        = new CardModel();
        card.target      = obj.target      ;
        card.damage      = obj.damage      ; 
        card.rounds      = obj.rounds      ;
        card.cardType    = obj.cardType    ;
        card.cardScale   = obj.cardScale   ;
        return card;
    }

    toJSON(){
        return `
        {
            "target   " : "${this.target      }",   
            "damage   " : "${this.damage      }",   
            "rounds   " : "${this.rounds      }",   
            "cardType " : "${this.cardType    }",   
            "cardScale" : "${this.cardScale   }"   
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
        if(model.cardType == 0)
            this.element.classList.add("OFFENSIVE_CARD");
        else 
            this.element.classList.add("DEFENSIVE_CARD");
 
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

 