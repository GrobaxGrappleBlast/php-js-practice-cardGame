import { Card , Dock , Constants} from './main.js'
 
class BoardSide_model { 

    offensive_row;
    defensive_row;
    handCards; 

    constructor( slots ){
        this.offensive_row = new Array(slots);
        this.defensive_row = new Array(slots);
        this.handCards = [];
    } 

    static fromJSON(json, slots=8){
        let rtr = new BoardSide_model(slots);
        let obj = JSON.parse(json == null ? "{}" : json);
      
        for (let i = 0; i < rtr.offensive_row.length; i++) {
          if (obj.offensive_row !== undefined && obj.offensive_row[i] !== undefined) {
            rtr.offensive_row[i] = Dock.fromJSON(JSON.stringify(obj.offensive_row[i]));
          } else {
            rtr.offensive_row[i] = new Dock();
          }
        }
      
        for (let i = 0; i < rtr.defensive_row.length; i++) {
          if (obj.defensive_row !== undefined && obj.defensive_row[i] !== undefined) {
            rtr.defensive_row[i] = Dock.fromJSON(JSON.stringify(obj.defensive_row[i]));
          } else {
            rtr.defensive_row[i] = new Dock();
          }
        }
      
        for (let i = 0; i < rtr.handCards.length; i++) {
            if (obj.handCards !== undefined && obj.handCards[i] !== undefined) {
                rtr.handCards.push( 
                    Dock.fromJSON(JSON.stringify(obj.handCards[i])) 
                );
            }
        }
        
        return rtr;
    } 
 
    rowjson(arr){
        let rtr = "";
        let first = true;
        arr.forEach  ( dock => {
            if(first){
                first = false;
                rtr += ",\n";
            }
            rtr += dock.toJSON();
        }); 
    }

    toJSON(){
 
        return `
        {
          "offensive_row":[`+ rowjson(this.offensive_row) +`],
          "defensive_row":[`+ rowjson(this.defensive_row) +`],
          "handCards":[`+ rowjson(this.handCards) +`]
        }
        `
    }

    addToHand(Card){
        this.handCards.push(Card);
    }
}

class BoardSide_View { 
    container;
    board ;
    tbody   ;    
    offensive_row;
    defensive_row; 
    hand ; 
    constructor( ){
         
        // Create elements
        this.container  = document.createElement("div");
        this.hand       = document.createElement("div");
        this.board      = document.createElement("div");
        // create elements for the board;
        this.tbody = document.createElement("div");
        this.offensive_row = document.createElement("div");
        this.defensive_row = document.createElement("div");

        // give elements classes,
        this.board.classList.add(Constants.CARDS_DECK_TABLE_CLASS); 
        this.offensive_row.classList.add("offensiveRow"); 
        this.defensive_row.classList.add("defensiveRow"); 
        this.offensive_row.classList.add("cardRow"); 
        this.defensive_row.classList.add("cardRow"); 
        this.hand.classList.add(Constants.CARDS_HANDDECK_TABLE_CLASS);

        // append Elements
        this.container.appendChild(this.board);
        this.board.appendChild(this.tbody);
        this.tbody.appendChild(this.offensive_row)
        this.tbody.appendChild(this.defensive_row)
        this.container.appendChild(this.hand);

        

    } 
    render(model){
       
        this.offensive_row.innerHTML="";
        this.defensive_row.innerHTML="";

        model.offensive_row.forEach( dock => {
            dock.attachToParent(this.offensive_row);
        });

        model.defensive_row.forEach( dock =>{
            dock.attachToParent(this.defensive_row);
        }) 

        // css necesary attr
        this.container.setAttribute('data-num-cards', this.offensive_row.length);
    }  
    addToHand(card){
        card.dockAt(this.hand);
    } 
} 

export class BoardSide { 
    
    model;
    view ; 

    constructor( slots, json = null){ 

        if( json == null ){
            this.model = BoardSide_model.fromJSON(json);
        }else{
            this.model = new BoardSide_model(slots);
        }
        this.view = new BoardSide_View();  
        
    }  

    asHTML(){
        return this.view.container;
    }

    toJSON(){
        return this.model.toJSON();
    }
    
    render(){
        this.view.render(this.model);
    }   

    addToHand(card){
        this.view.addToHand(card); 
    }

    activate(){

    }

    showHand(){

    }

}   