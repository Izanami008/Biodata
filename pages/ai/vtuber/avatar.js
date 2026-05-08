const avatar = document.getElementById("avatar");
const bubble = document.getElementById("bubble");

const faces = {
  normal: "ai/img/vtuber.png",
  happy: "ai/img/vtuber-happy.png",
  sad: "ai/img/vtuber-sad.png",
  confused: "ai/img/vtuber-confused.png",
  blink: "ai/img/vtuber-eyes-closed.png",
  mouth: "ai/img/vtuber-mouth-open.png"
};

let talking = false;
let mouthTimer = null;
let currentEmotion = "normal";

function setFace(name) {
  currentEmotion = name;
  avatar.src = faces[name] || faces.normal;
}

function blinkLoop() {
  setInterval(() => {
    if (!avatar) return;

    avatar.src = faces.blink;

    setTimeout(() => {
      if (talking) {
        avatar.src = faces.mouth;
      } else {
        avatar.src = faces[currentEmotion] || faces.normal;
      }
    }, 140);

  }, 4200);
}

function startTalking() {
  talking = true;

  if (mouthTimer) clearInterval(mouthTimer);

  mouthTimer = setInterval(() => {
    avatar.src =
      avatar.src.includes("mouth-open")
        ? (faces[currentEmotion] || faces.normal)
        : faces.mouth;
  }, 130);
}

function stopTalking() {
  talking = false;

  if (mouthTimer) {
    clearInterval(mouthTimer);
    mouthTimer = null;
  }

  avatar.src = faces[currentEmotion] || faces.normal;
}

function detectEmotion(text) {
  const t = text.toLowerCase();

  if (
    t.includes("halo") ||
    t.includes("terima kasih") ||
    t.includes("bagus") ||
    t.includes("senang")
  ) {
    return "happy";
  }

  if (
    t.includes("sedih") ||
    t.includes("kecewa") ||
    t.includes("capek")
  ) {
    return "sad";
  }

  if (
    t.includes("kenapa") ||
    t.includes("gimana") ||
    t.includes("bagaimana") ||
    t.includes("bingung")
  ) {
    return "confused";
  }

  return "normal";
}

function vtuberSpeak(text) {
  if (!window.speechSynthesis) return;

  speechSynthesis.cancel();

  const emotion = detectEmotion(text);
  setFace(emotion);

  const u = new SpeechSynthesisUtterance(text);
  u.lang = "id-ID";

  u.onstart = () => {
    startTalking();
  };

  u.onend = () => {
    stopTalking();
  };

  bubble.textContent = text;

  speechSynthesis.speak(u);
}

blinkLoop();
setFace("normal");
