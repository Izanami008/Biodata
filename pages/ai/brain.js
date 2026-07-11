const memory = JSON.parse(localStorage.getItem("izanami_ai") || "{}");

memory.history = Array.isArray(memory.history) ? memory.history : [];
memory.profile = memory.profile || {};
memory.facts = Array.isArray(memory.facts) ? memory.facts : [];

function saveMemory() {
  localStorage.setItem("izanami_ai", JSON.stringify(memory));
}

function remember(role, text) {
  if (!text || typeof text !== "string") return;

  memory.history.push({
    role,
    text: text.trim(),
    time: Date.now()
  });

  if (memory.history.length > 200) {
    memory.history = memory.history.slice(-100);
  }

  saveMemory();
}

function rememberFact(key, value) {
  if (!key || !value) return;

  const found = memory.facts.find(f => f.key === key);

  if (found) {
    found.value = value;
    found.updated = Date.now();
  } else {
    memory.facts.push({
      key,
      value,
      updated: Date.now()
    });
  }

  saveMemory();
}

function getFact(key) {
  const item = memory.facts.find(f => f.key === key);
  return item ? item.value : null;
}

function recentContext(limit = 8) {
  return memory.history
    .slice(-limit)
    .map(x => `${x.role}: ${x.text}`)
    .join("\n");
}

function extractFacts(text) {
  const t = text.toLowerCase();

  if (t.includes("nama saya")) {
    const value = text.replace(/nama saya/i, "").trim();
    if (value) rememberFact("name", value);
  }

  if (t.includes("saya suka")) {
    const value = text.replace(/saya suka/i, "").trim();
    if (value) rememberFact("likes", value);
  }

  if (t.includes("saya kerja di")) {
    const value = text.replace(/saya kerja di/i, "").trim();
    if (value) rememberFact("work", value);
  }
}

function brain(text) {

  remember("user", text);
  extractFacts(text);

  const t = text.toLowerCase().trim();

  const name = getFact("name");
  const likes = getFact("likes");
  const work = getFact("work");

  let reply = "";

  if (t.includes("siapa nama saya")) {
    reply = name
      ? `Nama kamu ${name}.`
      : "Kamu belum pernah memberi tahu namamu.";
  }

  else if (t.includes("apa yang saya suka")) {
    reply = likes
      ? `Kamu pernah bilang suka ${likes}.`
      : "Aku belum tahu apa yang kamu suka.";
  }

  else if (t.includes("kerja saya dimana")) {
    reply = work
      ? `Kamu pernah bilang bekerja di ${work}.`
      : "Aku belum tahu tempat kerjamu.";
  }

  else if (t.includes("ringkas profil saya")) {

    const info = [];

    if (name) info.push(`Nama: ${name}`);
    if (likes) info.push(`Suka: ${likes}`);
    if (work) info.push(`Kerja: ${work}`);

    reply = info.length
      ? info.join("\n")
      : "Belum ada informasi yang kusimpan.";
  }

  else if (
    t === "halo" ||
    t === "hai" ||
    t.startsWith("halo ") ||
    t.startsWith("hai ")
  ) {
    reply = name
      ? `Halo ${name}, ada yang bisa kubantu?`
      : "Halo, ada yang bisa kubantu?";
  }

  else {

    const ctx = recentContext(6);

    reply = ctx
      ? `Aku masih mengingat konteks percakapan sebelumnya.\n\n${ctx}\n\nSilakan lanjutkan pertanyaanmu.`
      : "Coba jelaskan lebih detail supaya aku bisa membantu.";
  }

  remember("ai", reply);

  return reply;
}

function clearMemory() {
  if (confirm("Hapus semua memori AI?")) {
    localStorage.removeItem("izanami_ai");
    location.reload();
  }
    }
