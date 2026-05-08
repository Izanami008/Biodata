async function askOnlineAI(message, history = []) {
  const endpoint = "./api/chat.php";

  const res = await fetch(endpoint, {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      message,
      history
    })
  });

  if (!res.ok) {
    throw new Error("AI request failed");
  }

  const data = await res.json();

  return data.reply || "Tidak ada balasan dari AI.";
}
