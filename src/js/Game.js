import { Card, Constants ,  DraggingHandler, Player, HumanPlayer,AIPlayer} from './main.js';

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
    
    async getOffensiveCards(count) {
        let response = await fetch('src/php/api/CreateCards.php?request=offensive&count=' + count);
        let data = await response.json(); 
        return data;
    }
      
    async getDefensiveCards(count) {
        let response = await fetch('src/php/api/CreateCards.php?request=defensive&count=' + count);
        let data = await response.json(); 
        return data;
    }

    async start(rounds){ 
        // todo, move this out of start and into a constructor or something.
        // todo reconsider last todo.
        // give Cards to Eeach Player
        for (let i = 0; i < this.players.length ; i++) {
            let offCards = [];
            let defCards = [];
            
            let _;
            _ = await this.getOffensiveCards(8);
            
            _.forEach( card => {
                let a = JSON.stringify(card);
                let b = Card.fromJSON(a);
                offCards.push( Card.fromJSON( JSON.stringify(card) ) );
            });

            _ = await this.getDefensiveCards(8);
            
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
 
 
