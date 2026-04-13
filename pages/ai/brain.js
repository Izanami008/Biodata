function brain(text){

text = text.toLowerCase();

if(text.includes("halo")) return "Halo juga!";
if(text.includes("btc")) return "BTC sedang bergerak, cek chart ya!";
if(text.includes("siapa kamu")) return "Aku AI teman kamu 🤍";

return "Aku belum paham, coba lagi ya.";
}
