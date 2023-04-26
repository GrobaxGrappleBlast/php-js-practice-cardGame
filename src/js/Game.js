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

    registerPlayer( player ){

    }

}