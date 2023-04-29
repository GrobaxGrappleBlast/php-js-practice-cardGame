import { DraggingHandler, apiCaller , PlayerBoard, generalMethods,Game } from "./main.js";

 
export class Player{
    
    name;
    board; 
 
    constructor(playerBoard, name){
        this.name = name;
        this.board = playerBoard;
    }

    async takeTurn(){
        console.error("NOT IMPLEMENTED");
    }

    activate(){
        this.board.activate();
    }

    deactivate(){
        this.board.deactivate();
    }

    addToHand(card){
      this.board.addToHand(card);
    }

    addCardsToHand(cards){
       this.board.addCardsToHand(cards);
    }

    getDTO(){
        return {
            name : this.name,
            board: this.board.getDTO()
        }
    }
 
}

export class HumanPlayer extends Player{
    async takeTurn(){ 
        let hasMoved_offensive = false;
        let hasMoved_defensive = false;
        
        let off_card;
        let def_card;
        
        function onOffensiveCardListener(card){
            hasMoved_offensive = true;
            off_card           = card;
        }
        
        function onDefensiveCardListener(card){
            hasMoved_defensive  = true;
            def_card            = card;
        } 

        this.activate();
        DraggingHandler.enableTurn( onOffensiveCardListener,onDefensiveCardListener );
        while(true){
            if(hasMoved_offensive && hasMoved_defensive){
                this.deactivate();
                return;
            }
            await generalMethods.sleep(300)
        }  
    }
}
export class AIPlayer extends Player {

    createOponentsDTO(oponents){
        let arr = [];
        oponents.forEach( oponent  => {
            arr.push(oponent.getDTO());
        });
        return arr;
    }

    async takeTurn(){ 

        const game      = Game.getInstance();
        const oponents  = game.getOpponents(this.name);

        const data = await apiCaller.callAIMove(this.board.getDTO(), this.createOponentsDTO(oponents)  );
        
        let card_0 = this.board.AI_getCard(data.move[0]);
        let card_1 = this.board.AI_getCard(data.move[1]);

        alert(card_0);
    }
}