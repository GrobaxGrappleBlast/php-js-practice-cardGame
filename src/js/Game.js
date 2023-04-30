import { Card, Constants ,apiCaller,  DraggingHandler, Player, HumanPlayer,AIPlayer} from './main.js';

export class Game{
 
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
        let offensive_cards = await apiCaller.CallGetCards_Offensive( this.players.length * rounds );
        let defensive_cards = await apiCaller.CallGetCards_Defensive( this.players.length * rounds );

        // Give Players Cards;
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
 
        // Start Rounds
        this.startGameLoop(rounds);
    }

    currentPlayer;
    async startGameLoop(rounds){

        let playerQueue = [];
        for( let i = 0; i < this.players.length; i++ ){
            playerQueue.push(this.players[i]);
        }

        for (let r = 0; r < rounds; r++) { 
            console.log("ROUND " + ( r + 1) +" BEGIN!") ;  
             
            // All Players Are allowed to Pick Cards
            for( let i = 0; i < this.players.length; i++ ){
                console.log("PLAYER " + (i+1))
                // Select A player and Unlock The Board;
                this.currentPlayer = this.players[i];
                this.currentPlayer.activate();

                // Let the Player have their turn;
                await this.currentPlayer.takeTurn();
                this.currentPlayer.deactivate();
            }
              
            // Calculate all players defense 
            playerQueue.forEach(p=>{
                p.calc_defense();
            }) 

            // After The Rounds the game Calculates Damage
            // Damage every player
            let attackingPlayer;
            let target;
            let attack; 
            for (let i = 0; i < this.players.length; i++) {
                console.log("PLAYER " + (i+1));
                attackingPlayer = playerQueue.shift(); 
                attack = attackingPlayer.calc_offense();
                for (let a = 0; a < playerQueue.length; a++) {
                    target = playerQueue[a];
                    target.takeDamage(attack);
                } 
                playerQueue.push(attackingPlayer);
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
 
 
