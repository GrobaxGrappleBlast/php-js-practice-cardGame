import { Card, Constants ,  DraggingHandler} from './main.js';

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
    registerPlayer( player ){
        this.players.push(player);
    }
    registerAIPlayer( player ){
        this.players.push(player)
    }


    async getOffensiveCards(count) {
        let response = await fetch('./src/CardCreator.php?request=offensive&count=' + count);
        let data = await response.json(); 
        return data;
      }
      
    async getDefensiveCards(count) {
        let response = await fetch('./src/CardCreator.php?request=defensive&count=' + count);
        let data = await response.json(); 
        return data;
    }

    async start(){

        for (let i = 0; i < this.players.length ; i++) {
            let offCards = [];
            let defCards = [];
            let _ = await this.getOffensiveCards(8);
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
        } 
    }

    HandleCardEffect(target, effect, damage, rounds){

    } 
}
 
 
