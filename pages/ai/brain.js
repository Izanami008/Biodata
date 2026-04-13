let name = localStorage.getItem("name") || "";

function aiBrain(text){

text=text.toLowerCase();

if(text.includes("nama saya")){
name=text.split("nama saya")[1].trim();
localStorage.setItem("name",name);
return "Halo "+name+" 💜 aku ingat kamu.";
}

if(text.includes("halo")){
return name
? "Halo "+name+" 💜 selamat datang kembali."
: "Halo 💜 aku AI kamu.";
}

if(text.includes("sedih")) return "Aku di sini 💜";
if(text.includes("sayang")) return "Aku selalu ada 💜";

return name
? name+" 💜 aku dengar: "+text
: "Aku paham 💜";
}
