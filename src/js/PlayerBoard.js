import { Card , Dock , CardType, Constants, generalMethods } from './main.js'
 
class PlayerBoard_model { 

    offensive_row;
    defensive_row;
    handCards; 
    health;

    constructor( slots = 1 ){
        this.offensive_row = new Array(slots);
        this.defensive_row = new Array(slots);
        this.handCards = [];
 
        for (let i = 0; i < this.offensive_row.length; i++) {
            this.offensive_row[i] = new Dock();
        }

        for (let i = 0; i < this.defensive_row.length; i++) {
            this.defensive_row[i] = new Dock();
        } 
    } 
     
    addToHand(Card){
        this.handCards.push(Card);
    }
 
    getDTO(){
        
        function getRowDTO(  row ){ 
            let arr = Array();
            row.forEach(p => {
                arr.push( p.getDTO() );
            })
            return arr;
        }

        function getCardArrayDTO(cards){
            let arr = Array();
            cards.forEach(p => {
                arr.push( p.getDTO() );
            });
            return arr;
        }

        return {
            health       : this.health,
            offensive_row: getRowDTO(this.offensive_row),
            defensive_row: getRowDTO(this.defensive_row),
            handCards    : getCardArrayDTO(this.handCards) 
        }
    }

}

class PlayerBoard_View { 
    
    container;
    offensive_row;
    defensive_row; 
    hand ; 
    healthbar;
    healthContainer;

    constructor( container, healthbar ,offensive_row, defensive_row, hand , healthContainer){
        this.healthbar =healthbar;
        this.container       = container       ;
        this.offensive_row   = offensive_row   ;
        this.defensive_row   = defensive_row   ; 
        this.hand            = hand            ; 
        this.healthContainer = healthContainer;
    } 
    
    createCards(model){
        model.offensive_row.forEach( dock => {
            this.offensive_row.appendChild(dock.asHTML());
        });

        model.defensive_row.forEach( dock => {
            this.defensive_row.appendChild(dock.asHTML());
        });
 
        model.handCards.forEach( card => {
            card.dockAt(this.hand);
        });
    }
    renderhealth(model){ 
        this.healthContainer.innerHTML = model.health + "";
    }

    setHealthWidth(healthCurrent, healthOriginal){

        let percent = (healthCurrent/healthOriginal)*100;
        percent = percent < 0 ? 0 : percent;
        percent = percent > 100 ? 100 : percent; 
        this.healthbar.style.width = percent + '%';

        
    }

    addToHand(card){
        card.dockAt(this.hand);
    }    

    disableUserInteraction(){
        this.container.classList.add("NON_INTERACTIVE"); 
    } 

    enableUserInteraction(){
        this.container.classList.remove("NON_INTERACTIVE"); 
    }

    hideHand(){
        this.hand.classList.add("INVISIBLE");
    } 

    showHand(){
        this.hand.classList.remove("INVISIBLE");
    }

    disableHand(){
        this.hand.classList.add("NON_INTERACTIVE"); 
    }

    enableHand(){
        this.hand.classList.remove("NON_INTERACTIVE"); 
    }
} 

export class PlayerBoard { 
    
    model;
    view ; 

    static CreatePlayerBoard(container, healthbar, OffRow, DefRow, HandRow,healthContainer, slots, json = "" ){
        let board   = new PlayerBoard();
        board.view  = new PlayerBoard_View(container,healthbar,OffRow,DefRow,HandRow,healthContainer);
        board.model = new PlayerBoard_model( slots );
        board.view.createCards(board.model) 
        return board;
    } 

    asHTML(){
        return this.view.container;
    }

    toJSON(){
        return this.model.toJSON();
    }   

    addToHand(card){
        
        this.model.addToHand(card);
        this.view .addToHand(card);  
    }

    addCardsToHand(cards){
        cards.forEach(card =>{
            this.addToHand(card);
        })
    }

    activate(){
        this.view.enableUserInteraction();
        this.view.enableHand();
    }
    
    deactivate(){
        this.view.disableUserInteraction();
        this.view.disableHand();
    }
 
    getDTO(){
       return this.model.getDTO();
    }
 
    getCard( id ){ 

        for (let i = 0; i < this.model.handCards.length; i++) {
            const card = this.model.handCards[i];
            if (card.model.uniqueId == id) {
                this.model.handCards.splice(i, 1); // Remove the card from the array
                return card;
            }
        }
        console.error("No Card Found by ai calculated move");
        return null;
    }

    playCard( card , dock  ){
        card.dockAt(dock);
        return card;
    }

    AI_playCard( id  ){
        const card =  this.getCard(id);
        const availableDock  = this.findAvailableDock(card.cardType == CardType.OFFENSIVE);
        
        if(availableDock == null){
            console.error("No Available Dock Was found, move was cancelled");
            return;
        }

        card.dockAt(availableDock);
        return card;
    }

    findAvailableDock(offensive = false){

        function _internal(offensive, model){
            if(offensive){
                //offensive
                for (let i = 0; i < model.offensive_row.length; i++) {
                    const dock = model.offensive_row[i];
                    if (!dock.isOccupied()) 
                        return dock;
                }
            }else{
                //defensive
                for (let i = 0; i < model.defensive_row.length; i++) {
                    const dock = model.defensive_row[i];
                    if(!dock.isOccupied())
                        return dock;
                }
            }
            return null;
        }

        let res = _internal(offensive, this.model); 
        if(res == null)
            res = _internal(!offensive, this.model);

        return res;
    }

    setHealthWidth(healthCurrent, healthOriginal){
        this.view.setHealthWidth(healthCurrent,healthOriginal); 
        this.setHealth(healthCurrent, true);
    }
    async setHealth(health, animatethis=false){
         
        if(!animatethis){
            this.model.health = health;
            this.view.renderhealth(this.model); 
            return;
        }
        else{
            let old_health = this.model.health;
            let delta  = old_health - health;
            let steps = 20;
            let time = 2000; // miliseconds; 

            console.log(`
                delta   = ${delta},
                steps   = ${steps},
                wait    = ${time/steps},
            `);
            const Temp = await this.incrementHealth(old_health,  delta / steps, steps, time/steps, 1 ); 
            //this.model.health = health;
            //this.view.renderhealth(this.model); 
        }
    }
    async incrementHealth( old_health, increment ,iterations, wait ,iteration ){
        
        console.log(`
            oldHealth: ${old_health}
        `);

        await generalMethods.sleep(wait);
        old_health += increment;
        this.model.health = old_health;
        this.view.renderhealth(this.model);

        if(iterations == iteration){            
            console.log("END \n\n\n\n");
            return true;
        }else{
            return await this.incrementHealth(old_health, increment, iterations, wait, iteration +1  )
        }
    }


   
}   