import { Card, Constants ,  DraggingHandler} from './main.js';

class DockModel{  
    card; 
    static fromJSON(json){
        let model = new DockModel();
        let obj = JSON.parse(json);
        model.card = obj.card; 
        this.card = Card.fromJSON(obj.card);
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

}  
export class Dock { 
    model ;
    view  ; 
    constructor(json = null){
        if(json == null){
            this.model = new DockModel();
        }else{
            this.model = fromJSON(json);
        }
        
        this.view = new DockView();
        this.view.render(this.model); 

        DraggingHandler.addDockingDragListeners(this);
    }

    static fromJSON(json){
        this.model = DockModel.fromJSON(json);
    }

        toJSON(){
            return `
                {
                    type:"dock"` + this.model.card!=null ?`,
                    card:` + this.model.card.toJson():""
                    `
                }
            `
        }

    attachToParent(parent){
        this.view.attachToParent(parent);
    } 

    asHTML(){
       return this.view.element;
    }
    
    isOccupied(){
        return this.model.card != null;
    }
}

 