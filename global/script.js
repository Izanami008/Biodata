// ============================
// AMBIL ELEMENT
// ============================

const loading = document.getElementById("loading");
const video = document.getElementById("introVideo");


// ============================
// TAMPILKAN VIDEO
// ============================

setTimeout(() => {

    loading.style.display = "none";
    video.style.display = "block";
    video.play();

}, 2000);


// ============================
// SETELAH VIDEO SELESAI
// ============================

video.onended = function () {

    window.location.href = "global/global.html";

};


// ============================
// FALLBACK JIKA VIDEO ERROR
// ============================

setTimeout(() => {

    window.location.href = "global/global.html";

}, 10000);
