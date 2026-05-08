const memory = JSON.parse(localStorage.getItem("izanami_ai") || "{}");

memory.history = memory.history || [];
memory.profile = memory.profile || {};
memory.facts = memory.facts || [];

function saveMemory() {
  localStorage.setItem("izanami_ai", JSON.stringify(memory));
}

function remember(role, text) {
  memory.history.push({
    role,
    text,
    time: Date.now()
  });

  if (memory.history.length > 50) {
    memory.history = memory.history.slice(-50);
  }

  saveMemory();
}

function rememberFact(key, value) {
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

function recentContext(n = 6) {
  return memory.history
    .slice(-n)
    .map(x => x.text)
    .join(" | ");
}

function extractFacts(text) {
  const t = text.toLowerCase();

  if (t.includes("nama saya")) {
    const name = text.replace(/nama saya/i, "").trim();
    if (name) rememberFact("name", name);
  }

  if (t.includes("saya suka")) {
    const like = text.replace(/saya suka/i, "").trim();
    if (like) rememberFact("likes", like);
  }

  if (t.includes("saya kerja di")) {
    const work = text.replace(/saya kerja di/i, "").trim();
    if (work) rememberFact("work", work);
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
      : "Kamu belum memberi tahu nama kamu.";
  }

  else if (t.includes("apa yang saya suka")) {
    reply = likes
      ? `Kamu pernah bilang suka ${likes}.`
      : "Aku belum tahu apa yang kamu suka.";
  }

  else if (t.includes("kerja saya dimana")) {
    reply = work
      ? `Kamu pernah bilang kerja di ${work}.`
      : "Aku belum tahu tempat kerja kamu.";
  }

  else if (t.includes("ringkas profil saya")) {
    const parts = [];

    if (name) parts.push(`nama ${name}`);
    if (likes) parts.push(`suka ${likes}`);
    if (work) parts.push(`kerja di ${work}`);

    reply = parts.length
      ? `Yang aku ingat: ${parts.join(", ")}.`
      : "Belum ada profil yang tersimpan.";
  }

  else if (t.includes("halo") || t.includes("hai")) {
    reply = name
      ? `Halo ${name}. Ada yang mau dibahas hari ini?`
      : "Halo. Ada yang mau dibahas hari ini?";
  }

  else {
    const ctx = recentContext();

    reply = ctx
      ? `Aku masih mengikuti konteks percakapan kita. Jelaskan sedikit lebih spesifik. Konteks terakhir: ${ctx}`
      : "Ceritakan lebih detail, nanti aku bantu uraikan.";
  }

  remember("ai", reply);

  return reply;
}

function clearMemory() {
  localStorage.removeItem("izanami_ai");
  location.reload();
}
