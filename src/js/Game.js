import { Card, Constants ,apiCaller,  DraggingHandler, Player, HumanPlayer,AIPlayer} from './main.js';

export class Game{

    // singletong Implementation
    static instance;
    static getInstance(){
        if(Game.instance == null){
            Game.instance = new Game();
        }
        return Game.instance;
    }

    players = []
    registerPlayerBoard( board , name ){
        let player = new HumanPlayer(board, name);
        this.players.push(player);
    }

    registerAIPlayerBoard( board ){
        let player = new AIPlayer(board, name);
        this.players.push(player);
    }

    async start(rounds){ 
        
        // creating cards
        let offensive_cards = await apiCaller.CallGetCards_Offensive( this.players.length * 8 );
        let defensive_cards = await apiCaller.CallGetCards_Defensive( this.players.length * 8 );


        for (let i = 0; i < this.players.length ; i++) {
            let offCards = [];
            let defCards = [];
            
            let _ = offensive_cards.splice(0, 8);

            _.forEach( card => {
                let a = JSON.stringify(card);
                let b = Card.fromJSON(a);
                offCards.push( Card.fromJSON( JSON.stringify(card) ) );
            });

            _ = defensive_cards.splice(0, 8);
            
            _.forEach( card => {
                let a = JSON.stringify(card);
                let b = Card.fromJSON(a);
                defCards.push( Card.fromJSON( JSON.stringify(card) ) );
            });

            this.players[i].addCardsToHand(  offCards);
            this.players[i].addCardsToHand(  defCards ); 
            this.players[i].deactivate();  
        } 
 
        for (let r = 0; r < rounds; r++) { 
            console.log("ROUND " + ( r + 1) +" BEGIN!") ;   
            for( let i = 0; i < this.players.length; i++ ){
                console.log("PLAYER " + (i+1))
                this.players[i].activate();
                await this.players[i].takeTurn();
                this.players[i].deactivate();
            }
        }
    }
    
    getOpponents( exceptionPlayerName = "" ){
        let oponents = [];
        for (let i = 0; i < this.players.length; i++) {
            const player = this.players[i];
            if(player.name == exceptionPlayerName)
                continue;
            
            oponents.push(player);
        }
        return oponents;
    }

}
 
 
