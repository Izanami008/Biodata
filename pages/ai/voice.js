function speak(text) {
  if (!window.speechSynthesis) return;

  speechSynthesis.cancel();

  const u = new SpeechSynthesisUtterance(text);
  u.lang = "id-ID";
  u.rate = 1;

  speechSynthesis.speak(u);
}

function startListening() {
  const SpeechRecognition =
    window.SpeechRecognition || window.webkitSpeechRecognition;

  if (!SpeechRecognition) return;

  const rec = new SpeechRecognition();

  rec.lang = "id-ID";

  rec.onresult = e => {
    const text = e.results[0][0].transcript;
    document.getElementById("input").value = text;
    send();
  };

  rec.start();
}
