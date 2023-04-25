import { Card, Constants } from './main.js';

class DockModel{ 
 
    card;  
    static fromJSON(json){
        let obj = JSON.parse(json);
        this.card = Card.fromJSON(obj.card);
    }

} 
class DockView{ 

    element = document.createElement("td"); 
    
    constructor(){
        console.log(Constants.CARD_CLASS)
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
        this.view.render(this.model)

        this.view.element.addEventListener('dragover', (event) => {
            event.preventDefault(); // Required to allow a drop
        });
        
        this.view.element.addEventListener('drop', (event) => {
            event.preventDefault();
            let cardJson = event.dataTransfer.getData('card');
            let card = Card.fromJSON(cardJson);
            card.dockAt(this);
        });
    }

    static fromJSON(json){
        this.model = DockModel.fromJSON(json);
    }

    toJson(){
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
}

 