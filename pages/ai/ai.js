import { brain } from "./brain.js";
import { speak } from "./voice.js";

export function startAI(){

let rec=new (window.SpeechRecognition||window.webkitSpeechRecognition)();
rec.lang="id-ID";

rec.onresult=(e)=>{
let text=e.results[0][0].transcript;

let reply=brain(text);

document.getElementById("chat")?.innerHTML+=`
<div>You: ${text}</div>
<div>AI: ${reply}</div>
`;

speak(reply);
};

rec.start();
}
