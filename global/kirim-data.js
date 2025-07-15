async function kirimData() {
  const data = {
    ip: "123.123.123.123", // Ganti pakai IP API jika mau otomatis
    lokasi: "Indonesia",   // Bisa pakai Geo API nantinya
    device: navigator.userAgent,
    foto: "" // Base64 string gambar, bisa diisi nanti
  };

  const res = await fetch("simpan.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(data)
  });

  const hasil = await res.text();
  console.log(hasil); // Cek respon dari server (berhasil/gagal)
}
