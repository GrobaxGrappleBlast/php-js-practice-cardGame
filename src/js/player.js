import { Card , Dock , BoardSide, Constants } from './main.js'

class PlayerModel{
    hitPoints = 100;
    player_name = "Name Here";
    board // = new BoardSide();

    constructor(GameLayer, rounds){
        console.log("Creating Player");
        this.board = new BoardSide(GameLayer, rounds);
        this.board.render();
    }
}

class PlayerView{

    Hand;

    constructor(model){

    }

    render(){

    }
}


export class Player{
 
    model;
    view;

    constructor(parent, rounds){
        this.model = new PlayerModel(parent,rounds);
    }
    setActive(){

    }
    setPassive(){

    } 
    giveCards(cards){ 
        cards.forEach(card => {
            this.model.board.addToHand(card);
        }); 
    }
    giveCard(card){  
        this.model.board.addToHand(card); 
    }
}