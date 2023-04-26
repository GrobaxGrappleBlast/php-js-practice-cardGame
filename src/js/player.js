import { Card , Dock , BoardSide, Constants } from './main.js'

class PlayerModel{

    hitPoints = 100;
    player_name = "Name Here";
    board; 

    constructor(rounds){ 
        this.board = new BoardSide(rounds);
        this.board.render();
    }   
}

class PlayerView{

    PlayerContainer;
    PlayerBadge;
    healthBar;
    CardsContainer;

    constructor(){ 
    } 
    render(model){ 
        //if(this.PlayerContainer != null)
        //    this.PlayerContainer.innerHTML = "";
        this.PlayerContainer = document.createElement("div");
        this.PlayerBadge     = document.createElement("div");
        this.CardsContainer  = document.createElement("div");

        this.PlayerContainer.classList.add("PlayerContainer");
        this.PlayerBadge    .classList.add("PlayerBadge");
        this.CardsContainer .classList.add("PlayerBoardContainer");

        this.PlayerContainer.appendChild(this.PlayerBadge);
        this.PlayerContainer.appendChild(this.CardsContainer);
        this.CardsContainer .appendChild(model.board.asHTML())
        console.log("STOP HER");
    }
}
 
export class Player{ 
    model;
    view;

    constructor(parent, rounds){
        this.model = new PlayerModel(parent,rounds);
        this.view = new PlayerView();
        this.view.render(this.model);
        parent.appendChild(this.asHTML());
    }

    asHTML(){
        return this.view.PlayerContainer;
    }

    giveCards(cards){ 
        cards.forEach(card => {
            this.model.board.addToHand(card);
        }); 
    }
    
    giveCard(card){  
        this.model.board.addToHand(card); 
    }


    PlayCard(hand_index){

    }
}