function add(msg){
document.getElementById("chat").innerHTML+="<div>"+msg+"</div>";
}

function send(){
let t=document.getElementById("input").value;

add("You: "+t);

let reply=aiBrain(t);

add("AI: "+reply);

speak(reply);

document.getElementById("input").value="";
}

function voice(){
let r=new (window.SpeechRecognition||window.webkitSpeechRecognition)();
r.lang="id-ID";

r.onresult=(e)=>{
document.getElementById("input").value=e.results[0][0].transcript;
send();
};

r.start();
  }
