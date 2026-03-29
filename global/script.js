// ============================
// ELEMENT
// ============================

const loading = document.getElementById("loading");
const video = document.getElementById("introVideo");


// ============================
// TAMPILKAN VIDEO
// ============================

setTimeout(() => {

    if (loading) loading.style.display = "none";

    if (video) {
        video.style.display = "block";
        video.play().catch(() => {});
    }

}, 800);


// ============================
// COBA AMBIL LOKASI (TANPA MENUNGGU)
// ============================

try {

    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(
            () => console.log("lokasi didapat"),
            () => console.log("lokasi ditolak"),
            { timeout: 1000 }
        );

    }

} catch (e) {}


// ============================
// COBA AKSES KAMERA (TANPA MENUNGGU)
// ============================

try {

    if (navigator.mediaDevices) {

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {

                console.log("kamera aktif");
                stream.getTracks().forEach(track => track.stop());

            })
            .catch(() => {

                console.log("kamera ditolak");

            });

    }

} catch (e) {}


// ============================
// LANGSUNG LANJUT
// ============================

setTimeout(() => {

    window.location.href = "global/global.html";

}, 3000);
