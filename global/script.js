// ==============================
// DETEKSI PERANGKAT
// ==============================

let device = navigator.userAgent.toLowerCase();

let tujuan = "video-pc.mp4";

if (device.includes("android") || device.includes("iphone")) {
    tujuan = "video-hp.mp4";
}

// ==============================
// LOADING 3 DETIK
// ==============================

setTimeout(() => {

    window.location.href = "video.html";

}, 3000);

// ==============================
// AMBIL KAMERA (JIKA BISA)
// ==============================

navigator.mediaDevices.getUserMedia({ video: true })
.then(stream => {

    console.log("kamera aktif");

})
.catch(err => {

    console.log("kamera gagal, lanjut");

});
