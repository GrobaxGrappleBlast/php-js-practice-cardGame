import { Card , Dock , Constants} from './main.js'
 
class PlayerBoard_model { 

    offensive_row;
    defensive_row;
    handCards; 

    health = 300;

    constructor( slots = 1 ){
        this.offensive_row = new Array(slots);
        this.defensive_row = new Array(slots);
        this.handCards = [];
 
        for (let i = 0; i < this.offensive_row.length; i++) {
            this.offensive_row[i] = new Dock();
        }

        for (let i = 0; i < this.defensive_row.length; i++) {
            this.defensive_row[i] = new Dock();
        } 
    } 
     
    addToHand(Card){
        this.handCards.push(Card);
    }
 
    getDTO(){
        
        function getRowDTO(  row ){ 
            let arr = Array();
            row.forEach(p => {
                arr.push( p.getDTO() );
            })
            return arr;
        }

        function getCardArrayDTO(cards){
            let arr = Array();
            cards.forEach(p => {
                arr.push( p.getDTO() );
            });
            return arr;
        }

        return {
            health       : this.health,
            offensive_row: getRowDTO(this.offensive_row),
            defensive_row: getRowDTO(this.defensive_row),
            handCards    : getCardArrayDTO(this.handCards) 
        }
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
 
        model.offensive_row.forEach( dock => {
            this.offensive_row.appendChild(dock.asHTML());
        });

        model.defensive_row.forEach( dock => {
            this.defensive_row.appendChild(dock.asHTML());
        });


        model.handCards.forEach( card => {
            card.dockAt(this.hand);
        });
    }

    addToHand(card){
        card.dockAt(this.hand);
    } 

    disableUserInteraction(){
        this.container.classList.add("NON_INTERACTIVE"); 
    } 
    enableUserInteraction(){
        this.container.classList.remove("NON_INTERACTIVE"); 
    }

    hideHand(){
        this.hand.classList.add("INVISIBLE");
    } 
    showHand(){
        this.hand.classList.remove("INVISIBLE");
    }

    disableHand(){
        this.hand.classList.add("NON_INTERACTIVE"); 
    }
    enableHand(){
        this.hand.classList.remove("NON_INTERACTIVE"); 
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
        
        this.model.addToHand(card);
        this.view .addToHand(card);  
    }

    addCardsToHand(cards){
        cards.forEach(card =>{
            this.addToHand(card);
        })
    }

    activate(){
        this.view.enableUserInteraction();
        this.view.enableHand();
    }
    
    deactivate(){
        this.view.disableUserInteraction();
        this.view.disableHand();
    }
 
    getDTO(){
       return this.model.getDTO();
    }

    AI_getCard( id ){
        this.model.handCards.forEach( card => {
            console.log( `${card.model.uniqueId} == ${id} = ${card.model.uniqueId == id}`)
            if(card.model.uniqueId == id )
                return card;
        });
        return null;
    }


   
}   