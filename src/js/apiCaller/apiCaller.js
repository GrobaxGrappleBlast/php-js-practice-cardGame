 
export class apiCaller{
    static async callAIMove( player, oponents ){
        const inputData = { 
            player  : player,
            oponents: oponents         
        };  

        const response = await fetch('src/php/api/CalcAI.php', {
            method: 'POST',
            headers : {'Content-Type': 'application/json'},
            body: JSON.stringify(inputData)
        });
  
        const data = await response.json();
        return data;
    }

    static async CallGetCards_Offensive( count ){ 
        let response = await fetch('src/php/api/CreateCards.php?request=offensive&count=' + count);
        let data = await response.json();  
        return data;
    }

    
    static async CallGetCards_Defensive( count ){ 
        let response = await fetch('src/php/api/CreateCards.php?request=defensive&count=' + count); 
        let data = await response.json(); 
        return data;
    }
}