document.addEventListener('DOMContentLoaded', () => {
    

    const chatBtn = document.querySelector("#chatbot .toggle-button");
    const chatTab = document.querySelector("#chatbot .chat");
    const chatUserInputDiv = document.querySelector("#chatbot .user-input");
    const chatUserInput = document.querySelector("#chatbot .user-input textarea");
    const chatHistoryDiv = document.querySelector("#chatbot .chat .history");
    const chatQueryBtn = document.querySelector("#chatbot .user-input .send-query");
    const chatEraseHistoryBtn = document.querySelector("#chatbot .user-input .erase-history");
    const chatLoader = document.querySelector("#chatbot .loader");


    let chatHistory = Array();

    function submitUserQuery(query){
        let newObj = {sender: 'user', message: query}
        chatHistory.push(newObj);
        addTextToChat(newObj);
        fetchChatData();
        chatUserInput.blur();
        chatUserInput.value = "";
        chatUserInputDiv.classList.add("collapsed");
    }

    function addTextToChat(obj){
        let text = document.createElement("p");
        text.classList.add(obj.sender);
        text.textContent = obj.message;
        chatHistoryDiv.append(text);
        chatHistoryDiv.scrollTo(0, chatHistoryDiv.scrollHeight);
    }

    async function resetChat(){
        chatHistory.length = 0;
        fetchChatData();
    }

    async function fetchChatData() { 
        chatLoader.classList.remove("hidden");
        const url = "api.php";
        const query = chatHistory;
        const headers = new Headers();

        headers.append("Content-Type", "application/json");

        const response = await fetch(url, {
            method: "POST",
            body: JSON.stringify({query: query}),
            headers: headers,
        });
        if(response.status == 200){      
                
            const answer = await response.json();
            
            if (answer !== 'empty'){
                let newObj = {sender: 'assistant', message: answer}
                chatHistory.push(newObj);
                addTextToChat(newObj);
            }else{
                let messages = document.querySelectorAll("#chatbot .chat .history .user, #chatbot .chat .history .assistant");
                messages.forEach(el => {
                    el.remove();
                })
            }
        }

        chatLoader.classList.add("hidden");
        

    }


    chatBtn.addEventListener("click", () =>{
        chatTab.classList.toggle("closed");
        chatBtn.classList.toggle("closed");
    });

    chatUserInput.addEventListener("keydown", (e) => {
        if(e.key == 'Enter'){
            chatQueryBtn.click();
        }
    });

    chatQueryBtn.addEventListener('click', () => {
        if(chatUserInput.value){
            query = chatUserInput.value;
            console.log(query);
            submitUserQuery(query);
        }
    });

    chatUserInput.addEventListener("focus", () => {
        chatUserInputDiv.classList.remove("collapsed");
    });

    chatUserInput.addEventListener("focusout", (e) => {
        if(e.target !== chatUserInputDiv && e.target !== chatUserInput && e.target !== chatQueryBtn && e.target !== chatEraseHistoryBtn){
            chatUserInputDiv.classList.add("collapsed");
        }
    });

    chatEraseHistoryBtn.addEventListener("click", () => {
        resetChat();
    });

});