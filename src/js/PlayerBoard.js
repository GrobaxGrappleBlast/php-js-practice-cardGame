import { Card , Dock , Constants} from './main.js'
 
class PlayerBoard_model { 

    offensive_row;
    defensive_row;
    handCards; 

    constructor( slots = 1 ){
        this.offensive_row = new Array(slots);
        this.defensive_row = new Array(slots);
        this.handCards = [];

        console.log("STOP HER")
        for (let i = 0; i < this.offensive_row.length; i++) {
            this.offensive_row[i] = new Dock();
        }

        for (let i = 0; i < this.defensive_row.length; i++) {
            this.defensive_row[i] = new Dock();
        } 
    } 
    
    /*
    static fromJSON(json, slots=8){
        let rtr = new PlayerBoard_model(slots);
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
    }*/

    addToHand(Card){
        this.handCards.push(Card);
    }
}

class PlayerBoard_View { 
    
    container;
    offensive_row;
    defensive_row; 
    hand ; 

    constructor( container, healthbar ,offensive_row, defensive_row, hand ){
        this.container       = container       ;
        this.offensive_row   = offensive_row   ;
        this.defensive_row   = defensive_row   ; 
        this.hand            = hand            ; 
    } 
    
    render(model){

        console.log("DOCKING HERE");
        model.offensive_row.forEach( dock => {
            this.offensive_row.appendChild(dock.asHTML());
        });

        model.defensive_row.forEach( dock => {
            this.defensive_row.appendChild(dock.asHTML());
        });
 
    }

    addToHand(card){
        card.dockAt(this.hand);
    } 
} 

export class PlayerBoard { 
    
    model;
    view ; 

    static CreatePlayerBoard(container, healthbar, OffRow, DefRow, HandRow, slots, json = "" ){
        let board   = new PlayerBoard();
        board.view  = new PlayerBoard_View(container,healthbar,OffRow,DefRow,HandRow);
        board.model = new PlayerBoard_model( slots );
        board.view.render(board.model) 
        return board;
    } 

    asHTML(){
        return this.view.container;
    }

    toJSON(){
        return this.model.toJSON();
    } 

    addToHand(card){
        this.view.addToHand(card); 
    }
 
}   