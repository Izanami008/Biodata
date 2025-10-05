async function kirimData() {
  // Dapatkan IP (contoh statis dulu, bisa pakai API publik)
  let ip_address = "127.0.0.1";

  // Dapatkan lokasi (contoh pakai navigator)
  let lokasi = "";
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        lokasi = `${pos.coords.latitude},${pos.coords.longitude}`;
        kirim(ip_address, lokasi, navigator.userAgent, "foto_tes.jpg");
      },
      (err) => {
        console.warn("Gagal dapat lokasi:", err);
        kirim(ip_address, "Tidak diketahui", navigator.userAgent, "foto_tes.jpg");
      }
    );
  } else {
    kirim(ip_address, "Tidak support", navigator.userAgent, "foto_tes.jpg");
  }
}

function kirim(ip, lokasi, perangkat, foto) {
  fetch("simpan.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body:
      "ip_address=" + encodeURIComponent(ip) +
      "&lokasi=" + encodeURIComponent(lokasi) +
      "&perangkat=" + encodeURIComponent(perangkat) +
      "&foto=" + encodeURIComponent(foto),
  })
    .then((res) => res.text())
    .then((res) => console.log("Respon server:", res))
    .catch((err) => console.error("Error kirim data:", err));
}
