import { Card, Constants ,apiCaller,  DraggingHandler, Player, HumanPlayer,AIPlayer} from './main.js';

export class Game{
 
    _player_health = 1;
    static instance;
    static getInstance(){
        if(Game.instance == null){
            Game.instance = new Game();
        }
        return Game.instance;
    }

    static getFirstInstance( health = 300 ){

        if(Game.instance == null){
            Game.instance = new Game();
        }
        let game = Game.instance;
        game._player_health = health;

        if(this.players != undefined){
            this.players.forEach(p=>{
                p.setOriginalHealth(health);
            });
        }

        return Game.instance;
    }


    players = []
    registerPlayerBoard( board , name ){
        let player = new HumanPlayer(board, name, this._player_health);
        this.players.push(player);
    }

    registerAIPlayerBoard( board ){
        let player = new AIPlayer(board, name, this._player_health);
        this.players.push(player);
    }

    async start(rounds){ 
        for (let i = 0; i < this.players.length ; i++) {
            let p = this.players[i];
            console.log(`
                Player ${i},
                health ${p._health}
            `)
        } 

        // creating cards
        let offensive_cards = await apiCaller.CallGetCards_Offensive( this.players.length * rounds );
        let defensive_cards = await apiCaller.CallGetCards_Defensive( this.players.length * rounds );

        console.log("GOT " + offensive_cards.length + " Offensive Cards;" );
        console.log("GOT " + defensive_cards.length + " Defensive Cards;" );

        // Give Players Cards;
        for (let i = 0; i < this.players.length ; i++) {
            let offCards = [];
            let defCards = [];
            
            let _ = offensive_cards.splice(0, rounds);

            _.forEach( card => {
                //let a = JSON.stringify(card);
                //let b = Card.fromJSON(a);
                offCards.push( Card.fromJSON( JSON.stringify(card) ) );
            });

            _ = defensive_cards.splice(0, rounds);
            
            _.forEach( card => {
                //let a = JSON.stringify(card);
                //let b = Card.fromJSON(a);
                defCards.push( Card.fromJSON( JSON.stringify(card) ) );
            });

            this.players[i].addCardsToHand(  offCards );
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
            //console.log("ROUND " + ( r + 1) +" BEGIN!") ;  
             
            // All Players Are allowed to Pick Cards
            for( let i = 0; i < this.players.length; i++ ){
                //console.log("PLAYER " + (i+1))
                // Select A player and Unlock The Board;
                this.currentPlayer = this.players[i];
                this.currentPlayer.activate();

                // Let the Player have their turn;
                await this.currentPlayer.takeTurn();
                this.currentPlayer.deactivate();
            }
              
            // Calculate all players defense 
            for( let i = 0; i < this.players.length; i++ ){
                this.players[i].calc_defense();
            }

            // After The Rounds the game Calculates Damage
            // Damage every player
            let attackingPlayer;
            let target;
            let attack; 
            for (let i = 0; i < this.players.length; i++) { 
                attackingPlayer = playerQueue.shift(); 
                attack = attackingPlayer.calc_offense();
                for (let a = 0; a < playerQueue.length; a++) {
                    target = playerQueue.shift();
                    target.takeDamage(attack, a == 0);

                    if(target.isDead()){
                        alert(" Game is Over ");
                    }
                    
                    playerQueue.push(target);
                } 
                playerQueue.push(attackingPlayer);
                attackingPlayer.calc_downCards();
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
 
 
