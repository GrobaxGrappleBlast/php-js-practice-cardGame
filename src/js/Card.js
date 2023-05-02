
import {Constants, DraggingHandler, Dock} from './main.js'
export class CardTarget {
    static SELF = 0;
    static ENEMY = 1;
} 
export class CardType{
    static OFFENSIVE = 0;
    static DEFENSIVE = 1;
} 
export class CardScale{
    static RELATIVE = 0;
    static ABSOLUTE = 1;
}
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

    element         = document.createElement("div");
    roundCounter    = document.createElement("div");
    explaination    = document.createElement("div");
    damageContainer = document.createElement("div");

    constructor(model){
        // add Class 
        this.element.classList.add(Constants.CARD_CLASS); 
        this.element.appendChild(this.roundCounter);
        this.element.appendChild(this.explaination);
        this.element.appendChild(this.damageContainer);
        this.roundCounter.classList.add("card_roundCounter");

        if(model.cardType == 0)
            this.element.classList.add(Card.OffensiveCardClass);
        else 
            this.element.classList.add(Card.DefensiveCardClass);
    }

    render(model){ 
        
        if(model.rounds == 0){
            this.element.classList.remove(Card.OffensiveCardClass);
            this.element.classList.remove(Card.DefensiveCardClass);
            this.element.classList.add(Card.DeadCardClass);
        } 

        this.roundCounter.innerHTML = model.rounds; 
        this.explaination.innerHTML =  this.handleType(model);
        this.damageContainer.innerHTML = this.handleDamageOutput(model);

        return;
    }
 
    handleType(model){
        if ( model.cardType == CardType.DEFENSIVE ){
            return (model.target == CardTarget.ENEMY )?
            "Damage Negation \n":
            "Healing Self \n"
        }else{
            return (model.target == CardTarget.ENEMY )?
            "Damage Card \n":
            "Damage Bonus Pr Attack \n"
        }   
    }
    
    handleDamageOutput(model){

        let text = model.damage; 
        if ( model.cardScale == CardScale.RELATIVE ){
            text += "%";
        } 

        text += " damage"
        return text;
    }

}
export class Card { 
    
    static OffensiveCardClass = "OFFENSIVE_CARD";
    static DefensiveCardClass = "DEFENSIVE_CARD";
    static DeadCardClass      = "DEAD_CARD" ; 

    dock  ;
    model ;
    view  ; 
    
    constructor(json = null){ 
        if(json == null){
            this.model = new CardModel();
        }else{
            this.model = CardModel.fromJSON(json);
        }
        
        this.view = new CardView(this.model);
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
        if(this.dock instanceof Dock){ 
            this.dock.occupyDock(this);
        }
        else{
            this.dock.appendChild(this.view.element);
        }
    }

    asHTML(){
        return this.view.element;
    } 

    update(){
        this.view.render(this.model);
    }

    isAlive(){
        return this.model.rounds > 0;
    }

}

 