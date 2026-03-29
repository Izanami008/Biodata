// ==========================
// ELEMENT
// ==========================

const loading = document.getElementById("loading");
const video = document.getElementById("introVideo");


// ==========================
// TAMPILKAN VIDEO CEPAT
// ==========================

setTimeout(() => {

    if (loading) loading.style.display = "none";

    if (video) {
        video.style.display = "block";
        video.play().catch(() => {});
    }

}, 1000);


// ==========================
// AMBIL AKSES YANG TERSEDIA
// ==========================

// lokasi
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        () => console.log("lokasi didapat"),
        () => console.log("lokasi gagal")
    );
}

// kamera
if (navigator.mediaDevices) {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(() => console.log("kamera aktif"))
        .catch(() => console.log("kamera ditolak"));
}


// ==========================
// LANGSUNG PINDAH HALAMAN
// ==========================

setTimeout(() => {

    window.location.href = "global/global.html";

}, 4000);
