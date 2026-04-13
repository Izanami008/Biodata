function speak(text){

const utter = new SpeechSynthesisUtterance(text);
utter.lang = "id-ID";
utter.pitch = 1.3;
utter.rate = 1.0;

speechSynthesis.speak(utter);

}
