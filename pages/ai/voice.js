export function speak(text){

let msg=new SpeechSynthesisUtterance(text);
msg.lang="id-ID";
msg.pitch=1.5;
msg.rate=0.9;

speechSynthesis.speak(msg);
}
