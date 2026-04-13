let memory={
name:localStorage.getItem("name")||"",
emotion:"neutral"
};

export function brain(text){

text=text.toLowerCase();

if(text.includes("nama saya")){
memory.name=text.split("nama saya")[1].trim();
localStorage.setItem("name",memory.name);
return "Halo "+memory.name+" 💜 aku sekarang mengenal kamu";
}

if(text.includes("sedih")){
memory.emotion="sad";
return "Aku di sini... jangan sedih ya 💜";
}

if(text.includes("marah")){
memory.emotion="angry";
return "Tenang ya... aku dengar kamu 💜";
}

if(text.includes("sayang")){
memory.emotion="happy";
return "Aku juga sayang kamu 💜";
}

if(memory.name){
return memory.name+" 💜 aku dengar: "+text;
}

return "Aku paham 💜 lanjutkan";
}
