<?php
// Dapatkan nama file halaman sekarang untuk menandai menu aktif
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="fixed top-0 left-0 w-full z-50 bg-black/90 backdrop-blur border-b border-white/10">
  <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
    <!-- Logo -->
    <a href="index.php" class="flex items-center gap-3 font-bold tracking-wider text-white hover:text-red-500">
      <span class="text-red-500">SPK</span> WILD RIFT
    </a>

    <!-- Menu -->
    <div class="hidden md:flex gap-8 text-sm font-semibold">
      <a href="index.php" class="<?= $currentPage=='index.php' ? 'text-red-500' : 'hover:text-red-500' ?>">HOME</a>
      <a href="kriteria.php" class="<?= $currentPage=='kriteria.php' ? 'text-red-500' : 'hover:text-red-500' ?>">KRITERIA</a>
      <a href="alternatif.php" class="<?= $currentPage=='alternatif.php' ? 'text-red-500' : 'hover:text-red-500' ?>">ALTERNATIF</a>
      <a href="analisa.php" class="<?= $currentPage=='analisa.php' ? 'text-red-500' : 'hover:text-red-500' ?>">ANALISA</a>
      <a href="perhitungan.php" class="<?= $currentPage=='perhitungan.php' ? 'text-red-500' : 'hover:text-red-500' ?>">PERHITUNGAN</a>
      <a href="#tentang" class="<?= $currentPage=='index.php' ? 'hover:text-red-500' : 'hover:text-red-500' ?>">TENTANG SISTEM</a>
    </div>
  </div>
</nav>
