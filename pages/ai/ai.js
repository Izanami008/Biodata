let sending = false;

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

  if (sending) return;

  const input = document.getElementById("input");
  const text = input.value.trim();

  if (!text) return;

  sending = true;

  addMessage(text, "user");
  input.value = "";
  setTyping(true);

  try {

    const history =
      (window.memory && memory.history)
        ? memory.history.slice(-20)
        : [];

    const controller = new AbortController();

    const timeout = setTimeout(() => {
      controller.abort();
    }, 30000);

    const res = await fetch("./api/chat.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        message: text,
        history
      }),
      signal: controller.signal
    });

    clearTimeout(timeout);

    if (!res.ok) {
      throw new Error("HTTP " + res.status);
    }

    const data = await res.json();

    const reply =
      data.reply ||
      "Maaf, AI tidak memberikan jawaban.";

    if (typeof remember === "function") {
      remember("user", text);
      remember("ai", reply);
    }

    addMessage(reply, "ai");

    if (typeof vtuberSpeak === "function") {
      vtuberSpeak(reply);
    } else if (typeof speak === "function") {
      speak(reply);
    }

  } catch (err) {

    console.error(err);

    const reply = brain(text);

    addMessage(reply, "ai");

    if (typeof vtuberSpeak === "function") {
      vtuberSpeak(reply);
    } else if (typeof speak === "function") {
      speak(reply);
    }

  } finally {

    setTyping(false);
    sending = false;

    document.getElementById("input").focus();

  }

}

document.addEventListener("DOMContentLoaded", () => {

  addMessage("Halo 👋 Saya Izanami AI. Ada yang bisa saya bantu?", "ai");

  document
    .getElementById("input")
    .addEventListener("keydown", e => {

      if (e.key === "Enter") {
        e.preventDefault();
        send();
      }

    });

});
