function send(){
let input = document.getElementById("input").value;
let chat = document.getElementById("chat");

chat.innerHTML += "<p><b>Kamu:</b> "+input+"</p>";

let reply = brain(input);

chat.innerHTML += "<p><b>AI:</b> "+reply+"</p>";

speak(reply);

document.getElementById("input").value="";
}
