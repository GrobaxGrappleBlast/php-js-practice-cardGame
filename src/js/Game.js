import { Card , Dock , BoardSide, Constants, Player} from './main.js'


class GameModelView{ 

    board;
    gameLayer;
    players = [];

    constructor(gameLayer){
        this.gameLayer = gameLayer;
        this.board = document.createElement("div");
        this.board.classList.add(Constants.BOARD_CLASS);
        this.gameLayer.appendChild(this.board);
    }


} 

export class Game{
    
    constructor( gameLayerId ){ 
        let gameLayer = document.getElementById(gameLayerId);
        if(gameLayer == null)
            alert("No Game Layer Was Found by id ==[" + gameLayerId+ "]"); 
        this.model = new GameModelView(gameLayer); 
    }
    
    start( num_players, rounds , cards_in_deck ){
        for (let index = 0; index < num_players; index++) {
            this.model.players.push( new Player(this.model.board, rounds) ) 
        }

        this.model.players.forEach(player => {
            let json = `[
                {
                    "type"    : "anyType",
                    "value"   : "1",
                    "command" : "1"
                },
                {
                    "type"    : "anyType",
                    "value"   : "2",
                    "command" : "1"
                },
                {
                    "type"    : "anyType",
                    "value"   : "3",
                    "command" : "1"
                },
                {
                    "type"    : "anyType",
                    "value"   : "4",
                    "command" : "1"
                },
                {
                    "type"    : "anyType",
                    "value"   : "5",
                    "command" : "1"
                },
                {
                    "type"    : "anyType",
                    "value"   : "6",
                    "command" : "1"
                }    
            ]`;
            let obj = JSON.parse(json);
  
            obj.forEach( objCard => {
                let card =  Card.fromJSON( JSON.stringify(objCard) );
                player.giveCard( card );
            }); 
        }); 
    } 
}
