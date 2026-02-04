<?php
session_start();
include 'configdb.php';
include 'functions.php';

if (!isset($_SESSION['judul'])) $_SESSION['judul'] = "SPK League of Legends";
if (!isset($_SESSION['by'])) $_SESSION['by'] = "Irhas Maulana S";

/* =========================
   AMBIL DATA
========================= */
$alt       = get_alternatif();
$alt_name  = get_alt_name();
$kep       = get_kepentingan();
$cb        = get_costbenefit();

$k = jml_kriteria();
$a = jml_alternatif();

/* =========================
   HITUNG WEIGHTED PRODUCT
========================= */
$tkep = array_sum($kep);
for ($i = 0; $i < $k; $i++) {
    $bkep[$i] = $kep[$i] / $tkep;
    $pangkat[$i] = ($cb[$i] === 'cost') ? -$bkep[$i] : $bkep[$i];
}

for ($i = 0; $i < $a; $i++) {
    $ss[$i] = 1;
    for ($j = 0; $j < $k; $j++) {
        $nilai = (!empty($alt[$i][$j]) && is_numeric($alt[$i][$j]) && $alt[$i][$j] > 0)
            ? $alt[$i][$j] : 1;
        $ss[$i] *= pow($nilai, $pangkat[$j]);
    }
}

$totalS = array_sum($ss);
for ($i = 0; $i < $a; $i++) {
    $v[$i] = round($ss[$i] / $totalS, 6);
}

/* =========================
   SORTING
========================= */
$sortedV = $v;
arsort($sortedV);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $_SESSION['judul'].' - '.$_SESSION['by']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
</head>

<body class="bg-black text-white">

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

<div class="pt-28 px-6 max-w-7xl mx-auto">

  <!-- HEADER -->
  <div class="mb-10">
    <h1 class="text-4xl md:text-5xl font-black text-red-500 mb-4">ANALISA</h1>
    <p class="text-white/80 max-w-2xl">
      Halaman ini menampilkan hasil analisa penilaian alternatif menggunakan metode
      <b>Weighted Product</b>.
    </p>
  </div>

<!-- ================= CHART ================= -->
<div class="bg-white/10 border border-white/20 rounded-xl p-6 mb-12">
  <h3 class="font-bold text-red-500 mb-4">Grafik Nilai Alternatif</h3>
  <canvas id="chart"></canvas>

  <div class="flex justify-center gap-4 mt-6">
    <button id="prevBtn" class="px-6 py-2 border rounded-full">Prev</button>
    <button id="nextBtn" class="px-6 py-2 border rounded-full">Next</button>
  </div>
</div>

<!-- ================= TABEL NILAI V ================= -->
<?php
$limitV = 10;
$pageV  = isset($_GET['page_v']) ? max(1, (int)$_GET['page_v']) : 1;
$totalV = count($sortedV);
$totalPageV = ceil($totalV / $limitV);
$offsetV = ($pageV - 1) * $limitV;
$paginatedV = array_slice($sortedV, $offsetV, $limitV, true);
?>

<div class="bg-white/10 border border-white/20 rounded-xl p-6 mb-12">
<h3 class="font-bold text-red-500 mb-4">Nilai Preferensi (V)</h3>

<table class="w-full">
<thead class="bg-red-500/20">
<tr>
<th class="p-3 text-left">Alternatif</th>
<th class="p-3 text-left">Nilai</th>
</tr>
</thead>
<tbody>
<?php foreach ($paginatedV as $i => $val): ?>
<tr class="border-b border-white/10">
<td class="p-3"><?= $alt_name[$i] ?></td>
<td class="p-3"><?= $val ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<!-- PAGINATION -->
<div class="flex justify-center items-center gap-2 mt-6">

<?php if ($pageV > 1): ?>
<a href="?page_v=<?= $pageV-1 ?>" class="px-4 py-2 border rounded-full">‹ Prev</a>
<?php endif; ?>

<?php for ($p = 1; $p <= $totalPageV; $p++): ?>
<a href="?page_v=<?= $p ?>"
   class="px-4 py-2 rounded-full border <?= $p == $pageV ? 'bg-red-500' : '' ?>">
<?= $p ?>
</a>
<?php endfor; ?>

<?php if ($pageV < $totalPageV): ?>
<a href="?page_v=<?= $pageV+1 ?>" class="px-4 py-2 border rounded-full">Next ›</a>
<?php endif; ?>

</div>
</div>

<!-- ================= TABEL PERINGKAT ================= -->
<?php
$limitR = 10;
$pageR  = isset($_GET['page_r']) ? max(1, (int)$_GET['page_r']) : 1;
$totalR = count($sortedV);
$totalPageR = ceil($totalR / $limitR);
$offsetR = ($pageR - 1) * $limitR;
$paginatedRank = array_slice($sortedV, $offsetR, $limitR, true);
?>

<div class="bg-white/10 border border-white/20 rounded-xl p-6 mb-12">
<h3 class="font-bold text-red-500 mb-4">Peringkat Alternatif</h3>

<table class="w-full">
<thead class="bg-red-500/20">
<tr>
<th class="p-3 text-center">Rank</th>
<th class="p-3 text-left">Alternatif</th>
<th class="p-3 text-center">Nilai</th>
</tr>
</thead>
<tbody>
<?php
$rank = $offsetR + 1;
foreach ($paginatedRank as $i => $val):
?>
<tr class="border-b border-white/10 <?= in_array($rank, [1,2,3]) ? 'bg-green-500/20' : '' ?>">
<td class="p-3 text-center font-bold"><?= $rank++ ?></td>
<td class="p-3"><?= $alt_name[$i] ?></td>
<td class="p-3 text-center"><?= $val ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="flex justify-center items-center gap-2 mt-6">
<?php if ($pageR > 1): ?>
<a href="?page_r=<?= $pageR-1 ?>" class="px-4 py-2 border rounded-full">‹ Prev</a>
<?php endif; ?>

<?php for ($p = 1; $p <= $totalPageR; $p++): ?>
<a href="?page_r=<?= $p ?>"
   class="px-4 py-2 rounded-full border <?= $p == $pageR ? 'bg-red-500' : '' ?>">
<?= $p ?>
</a>
<?php endfor; ?>

<?php if ($pageR < $totalPageR): ?>
<a href="?page_r=<?= $pageR+1 ?>" class="px-4 py-2 border rounded-full">Next ›</a>
<?php endif; ?>
</div>
</div>

<p class="text-center text-white/50 text-sm">© <?= $_SESSION['by'] ?></p>
</div>

<!-- ================= CHART SCRIPT ================= -->
<script>
const values = <?= json_encode(array_values($sortedV)); ?>;
const labels = <?= json_encode(array_map(fn($i)=>$alt_name[$i], array_keys($sortedV))); ?>;

let page = 0, perPage = 6, chart;

function renderChart(){
  let start = page * perPage;
  let data = values.slice(start, start+perPage);
  let lbls = labels.slice(start, start+perPage);

  if(chart) chart.destroy();

  chart = new Chart(document.getElementById('chart'),{
    type:'bar',
    data:{ labels:lbls, datasets:[{ data:data, backgroundColor:'rgba(239,68,68,.7)' }]},
    options:{ legend:{display:false} }
  });

  prevBtn.disabled = page === 0;
  nextBtn.disabled = start + perPage >= values.length;
}

prevBtn.onclick = ()=>{ if(page>0){page--;renderChart();}};
nextBtn.onclick = ()=>{ if((page+1)*perPage<values.length){page++;renderChart();}};
renderChart();
</script>

</body>
</html>
