<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SPK Pemilihan Karakter Wild Rift</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white font-sans antialiased">

<!-- NAVBAR -->
<nav class="fixed top-0 left-0 w-full z-50 bg-black/90 backdrop-blur border-b border-white/10">
  <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
    <!-- Logo -->
    <a href="index.php" class="flex items-center gap-3 font-bold tracking-wider text-white hover:text-red-500">
      <span class="text-red-500">SPK</span> WILD RIFT
    </a>

    <!-- Menu -->
    <div class="hidden md:flex gap-8 text-sm font-semibold">
      <a href="kriteria.php" class="hover:text-red-500">DATA KRITERIA</a>
      <a href="alternatif.php" class="hover:text-red-500">DATA ALTERNATIF</a>
      <a href="analisa.php" class="hover:text-red-500">ANALISA</a>
      <a href="perhitungan.php" class="hover:text-red-500">PERHITUNGAN</a>
      <a href="#tentang" class="hover:text-red-500">TENTANG SISTEM</a>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="relative min-h-screen w-full overflow-hidden">
  <!-- Background -->
  <img src="lol.jpg" class="absolute inset-0 w-full h-full object-cover opacity-60" />
  <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-transparent to-black"></div>

  <!-- Content -->
  <div class="relative z-10 flex flex-col items-center justify-center min-h-screen text-center px-6 pt-20">
    <h1 class="text-4xl md:text-6xl font-black tracking-tight text-red-500 mb-6">
      SISTEM PENDUKUNG KEPUTUSAN
    </h1>
    <p class="text-lg md:text-xl text-white/90 max-w-2xl mb-10">
      Pemilihan Karakter Terbaik pada Game <b>League of Legends: Wild Rift</b><br />
      Menggunakan Metode <b>Weighted Product (WP)</b>
    </p>

    <!-- CTA -->
    <div class="relative group">
      <div class="absolute -left-4 -top-4 w-full h-full border-2 border-white/70 transition-all group-hover:-left-2 group-hover:-top-2"></div>
      <a href="kriteria.php" class="relative z-10 inline-block bg-red-500 px-12 py-5 font-bold tracking-widest hover:scale-105 transition">
        MULAI PEMILIHAN
      </a>
    </div>

    <p class="mt-8 text-white/70 text-sm">
      *Tentukan Karakter terbaik dalam setiap pertandingan!
    </p>
  </div>
</section>

<!-- FITUR / LANGKAH -->
<section class="bg-[#ecf0f3] text-black py-24 px-6" id="tentang">
  <div class="max-w-7xl mx-auto">
    <!-- Judul Besar -->
    <div class="relative mb-24">
      <span class="absolute left-10 top-0 text-[120px] font-black opacity-10" style="color:transparent;-webkit-text-stroke:2px #d1d5db;">WEBSITE</span>
      <h2 class="relative text-4xl md:text-6xl font-black text-red-500">ALUR SISTEM</h2>
    </div>

    <div class="grid md:grid-cols-3 gap-12">
      <!-- Step 1 -->
      <div class="group">
        <div class="relative h-64 mb-6 overflow-hidden shadow-lg">
          <img src="step1.png" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" />
        </div>
        <h3 class="text-red-500 font-bold tracking-widest mb-2">LANGKAH PERTAMA</h3>
        <p>
          Pengguna melakukan input data kriteria dan bobot penilaian sesuai preferensi permainan.
        </p>
        <a href="kriteria.php" class="inline-block mt-4 text-sm font-semibold text-red-500">Input Kriteria →</a>
      </div>

      <!-- Step 2 -->
      <div class="group">
        <div class="relative h-64 mb-6 overflow-hidden shadow-lg">
          <img src="step2.png" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" />
        </div>
        <h3 class="text-red-500 font-bold tracking-widest mb-2">LANGKAH KEDUA</h3>
        <p>
          Sistem melakukan proses normalisasi bobot dan perhitungan menggunakan metode Weighted Product.
        </p>
        <a href="analisa.php" class="inline-block mt-4 text-sm font-semibold text-red-500">Lihat Analisa →</a>
      </div>

      <!-- Step 3 -->
      <div class="group">
        <div class="relative h-64 mb-6 overflow-hidden shadow-lg">
          <img src="step3.png" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" />
        </div>
        <h3 class="text-red-500 font-bold tracking-widest mb-2">LANGKAH KETIGA</h3>
        <p>
          Sistem menghasilkan ranking karakter dan menampilkan rekomendasi karakter terbaik.
        </p>
        <a href="perhitungan.php" class="inline-block mt-4 text-sm font-semibold text-red-500">Hasil Perhitungan →</a>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="bg-black text-center py-6 text-sm text-white/60">
  © 2026 — Sistem Pendukung Keputusan Pemilihan Karakter League of Legends: Wild Rift
</footer>

</body>
</html>