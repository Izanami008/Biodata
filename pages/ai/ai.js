function addMessage(text, type) {
  const chat = document.getElementById("chat");

  const div = document.createElement("div");
  div.className = `msg ${type}`;
  div.textContent = text;

  chat.appendChild(div);
  chat.scrollTop = chat.scrollHeight;
}

function setTyping(show) {
  document
    .getElementById("typing")
    .classList.toggle("hidden", !show);
}

async function send() {
  const input = document.getElementById("input");
  const text = input.value.trim();

  if (!text) return;

  addMessage(text, "user");

  if (typeof remember === "function") {
    remember("user", text);
  }

  input.value = "";
  setTyping(true);

  const history =
    (window.memory && memory.history)
      ? memory.history.slice(-20)
      : [];

  try {
    const reply = await askOnlineAI(text, history);

    setTyping(false);
    addMessage(reply, "ai");

    if (typeof remember === "function") {
      remember("ai", reply);
    }

    if (typeof vtuberSpeak === "function") {
      vtuberSpeak(reply);
    } else if (typeof speak === "function") {
      speak(reply);
    }

  } catch (e) {
    const reply = brain(text);

    setTyping(false);
    addMessage(reply, "ai");

    if (typeof vtuberSpeak === "function") {
      vtuberSpeak(reply);
    } else if (typeof speak === "function") {
      speak(reply);
    }
  }
}

document.addEventListener("DOMContentLoaded", () => {
  addMessage("Halo. Aku siap membantu.", "ai");

  document
    .getElementById("input")
    .addEventListener("keydown", e => {
      if (e.key === "Enter") {
        send();
      }
    });
});
