import { Card, Constants ,  DraggingHandler} from './main.js';

class DockModel{  
    card; 
    static fromJSON(json){
        let model = new DockModel();
        let obj = JSON.parse(json);
        model.card = obj.card; 
        this.card = Card.fromJSON(obj.card);
    }

    setCard(card){
        this.card = card;
    }
} 
class DockView{ 

    element = document.createElement("div"); 
    
    constructor(){
        this.element.classList.add(Constants.CARD_CLASS); 
        this.element.classList.add(Constants.DOCK_CLASS); 
    }

    render(model){ 
        if(model.card != undefined)
        this.view.innerHTML = this.model.card.render();
    }

    attachToParent(parent){
        parent.appendChild(this.element);
    }

    setOccupant(card){
        this.element.appendChild(card);
    }

}  
export class Dock { 
    model ;
    view  ; 
    constructor(json = null){
        if(json == null){
            this.model = new DockModel();
        }else{
            this.model = DockModel.fromJSON(json);
        }
        
        this.view = new DockView();
        this.view.render(this.model); 

        DraggingHandler.addDockingDragListeners(this);
    }

   
    getOccupant(){
        return this.model.card;
    }

    occupyDock(card){
        this.model.setCard(card);
        this.view.setOccupant(card.asHTML());
    }

    unOccupy(){
        this.model.card = null; 
    }

    isOccupied(){
        return this.model.card != null;
    }

    getDTO(){ 
        return {
            type : "dock",
            card : (this.model.card != null) ? this.model.card.getDTO() : "" 
        }
    }

    attachToParent(parent){
        this.view.attachToParent(parent);
    } 

    asHTML(){
       return this.view.element;
    }
    
    toString() {
        return `DOCK( Card:${this.model.card} )`;
    }
    


}

 