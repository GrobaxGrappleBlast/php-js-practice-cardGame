import {CardTarget, CardType, CardScale, DraggingHandler, apiCaller , PlayerBoard, generalMethods,Game } from "./main.js";

class PlayerRoundDefense{ 
    def_Healing_rel  = 0;
    def_Healing_abs  = 0;
    def_Negation_rel = 0;
    def_Negation_abs = 0;
    constructor(){}
}

class PlayerRoundDamage{ 
    off_Damage_rel = 0;
    off_Damage_abs = 0;
    off_Bonus_rel  = 0;
    off_Bonus_abs  = 0;
    constructor(){}
}  


export class Player{
    
        
    offensiveCards = [];
    defensiveCards = [];
    name;
    board; 
    _health=0;
    
    _originalHealth;
    _currentDefense;

    constructor(playerBoard, name, health){
        this._health =health;
        this._originalHealth = health;
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
            name    : this.name,
            health  : this._health,
            board   : this.board.getDTO()
        }
    } 

    takeDamage( playerOffense , firstAttack = true ){ 
         
        function calculate_total_relativeDamage(offense, defense, percentMethod, maxHealth) { 
            let relativeDamage = (offense.off_Damage_rel == 0 ) ? 0 : offense.off_Damage_rel + offense.off_Bonus_rel;
            relativeDamage = percentMethod(maxHealth, relativeDamage/100);
            relativeDamage = relativeDamage + offense.off_Bonus_abs; 
            // consider Defence
            relativeDamage = relativeDamage - defense.def_Negation_abs;  
            relativeDamage = percentMethod(relativeDamage, 1 - (defense.def_Negation_rel/100)); 
            if(relativeDamage < 0 )
                return 0 ;
            return relativeDamage;
        }
    
        function calculate_total_absoluteDamage(offense, defense, percentMethod) {
            let absoluteDamage = (offense.off_Damage_abs == 0 ) ? 0 : offense.off_Damage_abs + offense.off_Bonus_abs;
            absoluteDamage = percentMethod(absoluteDamage, 1 + ( offense.off_Bonus_rel / 100 ));
            // consider Defence
            absoluteDamage = absoluteDamage - defense.def_Negation_abs;  
            absoluteDamage = percentMethod(absoluteDamage, 1 - ( defense.def_Negation_rel/100)); 
            if(absoluteDamage < 0 )
                return 0 ;
            return absoluteDamage;
        }
        let offense =  playerOffense; 

        // set damage
        let health_max = this._health;
        let health_cur = this._health;

        if(firstAttack){
            // calc Healing, 
            this._health += this._currentDefense.def_Healing_abs;
            this._health = this.asPercent( this._health , 1 + (this._currentDefense.def_Healing_rel / 100));
        }

        // calc raw damage. 
        let relativeDamage = calculate_total_relativeDamage(offense,this._currentDefense,this.asPercent,health_max);
        let absoluteDamage = calculate_total_absoluteDamage(offense,this._currentDefense,this.asPercent,);
  
        // Take Damage!! WAHRRR! 
        //console.log(`DAMAGE REL ${relativeDamage} ABS ${absoluteDamage} RES ${relativeDamage + absoluteDamage} OFFENSE ${offense}`)
        health_cur -= (relativeDamage + absoluteDamage); 
        this._health = health_cur;

        this.board.setHealthWidth(this._health , this._originalHealth);
    }

    calc_downCards(){
        // count down card rounds : DEFENSIVE
        for (let i=0; i < this.defensiveCards.length ; i++) {  
            let card = this.defensiveCards[i]; 
            card.rounds -= 1;
            if(card.rounds == 0){
                this.defensiveCards.splice(i, 1); 
            } 
        }

        // count down card rounds : OFENSIVE
        for (let i=0; i < this.offensiveCards.length ; i++) {  
            let card = this.offensiveCards[i];
            card.rounds -= 1;
            if(card.rounds == 0){
                this.offensiveCards.splice(i, 1); 
            } 
        }
    }
    
    calc_defense(){ 
        // round defense;
        let roundDefense = new PlayerRoundDefense(); 
        // Every card needs to be added. 
        for ( let i=0; i < this.defensiveCards.length ; i++) {  
            let card = this.defensiveCards[i];
            switch(card.model.target){
                case CardTarget.SELF:
                    // interpreted as Healing for Self, thats why a defensive target is self.
                    this.addValueAndConsiderScaling(card, roundDefense,'def_Healing_rel', 'def_Healing_abs');
                    break;
                case CardTarget.ENEMY:
                    // interpreted as Damage Negation for Enemy Damage thats why a defensive target is self.
                    this.addValueAndConsiderScaling(card, roundDefense,'def_Negation_rel', 'def_Negation_abs');
                    break;
            } 
        }   
        this._currentDefense= roundDefense; 
    }

    calc_offense(){
        // round offense ; 
        let roundDamage = new PlayerRoundDamage(); 
        // Every card needs to be added. 
        for ( let i=0; i < this.offensiveCards.length ; i++) {  
            let card = this.offensiveCards[i];
            switch(card.model.target){
                case CardTarget.SELF:
                    // Calculate as Bonus damage
                    this.addValueAndConsiderScaling(card, roundDamage, 'off_Bonus_rel', 'off_Bonus_abs');
                    break;
                case CardTarget.ENEMY:
                    // Calulate as Damage
                    this.addValueAndConsiderScaling(card, roundDamage, 'off_Damage_rel', 'off_Damage_abs');
                    break;
            } 
        }
        return roundDamage;  
    }
    
    addValueAndConsiderScaling(card, roundDefense, relativeKey, absoluteKey){ 
        switch(card.model.cardScale){
            case CardScale.RELATIVE:
                roundDefense[relativeKey] += card.model.damage;
                break;
            case CardScale.ABSOLUTE:
                roundDefense[absoluteKey] += card.model.damage;
                break;
        }
    }

    asPercent($total, $percent){        
        return ( $total ) * $percent;
    } 

    playCard( card , dock = null ){ 
        const _card = this.board.playCard(card, dock);
        if(_card.model.cardType == CardType.DEFENSIVE){
            this.defensiveCards.push(_card);
        }else{
            this.offensiveCards.push(_card);
        } 
    }

    isDead(){
        return this._health <= 0;
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
        const data = await apiCaller.callAIMove(this.getDTO(), this.createOponentsDTO(oponents)  );
        
        this.AI_playCard(data.move[0]);
        this.AI_playCard(data.move[1]);
    }

    AI_playCard( id ){
        const card = this.board.AI_playCard(id);
        if(card.CardType == CardType.DEFENSIVE){
            this.defensiveCards.push(card);
        }else{
            this.offensiveCards.push(card);
        } 
    }
    
    activate(){
        //this.board.activate();
    }

}