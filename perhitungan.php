<?php
session_start();
include 'configdb.php';

if (!isset($_SESSION['judul'])) $_SESSION['judul'] = "SPK League of Legends";
if (!isset($_SESSION['by'])) $_SESSION['by'] = "Irhas Maulana S";

/* =========================
   FUNCTION
========================= */
function jml_kriteria(){
    include 'configdb.php';
    return $mysqli->query("SELECT * FROM kriteria")->num_rows;
}

function jml_alternatif(){
    include 'configdb.php';
    return $mysqli->query("SELECT * FROM alternatif")->num_rows;
}

function get_kepentingan(){
    include 'configdb.php';
    $q = $mysqli->query("SELECT kepentingan FROM kriteria");
    while($r = $q->fetch_assoc()) $data[] = $r['kepentingan'];
    return $data;
}

function get_costbenefit(){
    include 'configdb.php';
    $q = $mysqli->query("SELECT cost_benefit FROM kriteria");
    while($r = $q->fetch_assoc()) $data[] = $r['cost_benefit'];
    return $data;
}

function get_alt_name(){
    include 'configdb.php';
    $q = $mysqli->query("SELECT alternatif FROM alternatif");
    while($r = $q->fetch_assoc()) $data[] = $r['alternatif'];
    return $data;
}

function get_alternatif(){
    include 'configdb.php';
    $q = $mysqli->query("SELECT * FROM alternatif");
    while($r = $q->fetch_assoc()){
        $data[] = [
            $r['k1'],$r['k2'],$r['k3'],$r['k4'],$r['k5'],
            $r['k6'],$r['k7'],$r['k8'],$r['k9'],$r['k10'],$r['k11']
        ];
    }
    return $data;
}

/* =========================
   DATA
========================= */
$alt       = get_alternatif();
$alt_name  = get_alt_name();
$kep       = get_kepentingan();
$cb        = get_costbenefit();

$k = jml_kriteria();
$a = jml_alternatif();

/* =========================
   WEIGHTED PRODUCT
========================= */
$tkep = array_sum($kep);
for ($i=0;$i<$k;$i++){
    $bkep[$i] = $kep[$i]/$tkep;
    $pangkat[$i] = ($cb[$i]=='cost') ? -$bkep[$i] : $bkep[$i];
}

for ($i=0;$i<$a;$i++){
    $ss[$i] = 1;
    for ($j=0;$j<$k;$j++){
        $nilai = (is_numeric($alt[$i][$j]) && $alt[$i][$j]>0) ? $alt[$i][$j] : 1;
        $ss[$i] *= pow($nilai, $pangkat[$j]);
    }
}

$totalS = array_sum($ss);
for ($i=0;$i<$a;$i++){
    $v[$i] = round($ss[$i]/$totalS,6);
}

$sortedV = $v;
arsort($sortedV);

/* =========================
   PAGINATION SETUP
========================= */
$limit = 10;

/* Matrix */
$pageM = isset($_GET['page_m']) ? max(1,(int)$_GET['page_m']) : 1;
$totalPageM = ceil(count($alt)/$limit);
$matrixData = array_slice($alt, ($pageM-1)*$limit, $limit, true);

/* Nilai V */
$pageV = isset($_GET['page_v']) ? max(1,(int)$_GET['page_v']) : 1;
$totalPageV = ceil(count($v)/$limit);
$vData = array_slice($v, ($pageV-1)*$limit, $limit, true);

/* Ranking */
$pageR = isset($_GET['page_r']) ? max(1,(int)$_GET['page_r']) : 1;
$totalPageR = ceil(count($sortedV)/$limit);
$rankData = array_slice($sortedV, ($pageR-1)*$limit, $limit, true);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $_SESSION['judul'].' - '.$_SESSION['by']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-white font-sans antialiased">

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

<div class="pt-28 px-6 max-w-7xl mx-auto">

  <!-- HEADER -->
  <div class="mb-10">
    <h1 class="text-4xl md:text-5xl font-black text-red-500 mb-4">PERHITUNGAN</h1>
    <p class="text-white/80 max-w-2xl">
        Halaman ini menampilkan hasil secara mendetail perhitungan penilaian dengan menggunakan metode
      <b>Weighted Product</b>.
    </p>
  </div>

<!-- ================= MATRIX ================= -->
<div class="bg-white/10 border border-white/20 rounded-xl p-6 mb-12">
<h3 class="font-bold text-red-500 mb-4">Matrix Alternatif – Kriteria</h3>

<table class="w-full text-sm">
<thead class="bg-red-500/20">
<tr>
<th class="p-3">Alternatif</th>
<?php for($i=1;$i<=$k;$i++): ?><th class="p-3">K<?= $i ?></th><?php endfor; ?>
</tr>
</thead>
<tbody>
<?php foreach($matrixData as $i=>$row): ?>
<tr class="border-b border-white/10">
<td class="p-3 font-bold"><?= $alt_name[$i] ?></td>
<?php foreach($row as $v): ?><td class="p-3 text-center"><?= $v ?></td><?php endforeach; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="flex justify-center gap-2 mt-6">
<?php if($pageM>1): ?><a href="?page_m=<?= $pageM-1 ?>" class="px-4 py-2 border rounded-full">‹ Prev</a><?php endif; ?>
<?php for($p=1;$p<=$totalPageM;$p++): ?>
<a href="?page_m=<?= $p ?>" class="px-4 py-2 border rounded-full <?= $p==$pageM?'bg-red-500':'' ?>"><?= $p ?></a>
<?php endfor; ?>
<?php if($pageM<$totalPageM): ?><a href="?page_m=<?= $pageM+1 ?>" class="px-4 py-2 border rounded-full">Next ›</a><?php endif; ?>
</div>
</div>

<!-- ================= NILAI V ================= -->
<div class="bg-white/10 border border-white/20 rounded-xl p-6 mb-12">
<h3 class="font-bold text-red-500 mb-4">Nilai Preferensi (V)</h3>

<table class="w-full">
<thead class="bg-red-500/20">
<tr><th class="p-3">Alternatif</th><th class="p-3">Nilai</th></tr>
</thead>
<tbody>
<?php foreach($vData as $i=>$val): ?>
<tr class="border-b border-white/10">
<td class="p-3"><?= $alt_name[$i] ?></td>
<td class="p-3"><?= $val ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="flex justify-center gap-2 mt-6">
<?php if($pageV>1): ?><a href="?page_v=<?= $pageV-1 ?>" class="px-4 py-2 border rounded-full">‹ Prev</a><?php endif; ?>
<?php for($p=1;$p<=$totalPageV;$p++): ?>
<a href="?page_v=<?= $p ?>" class="px-4 py-2 border rounded-full <?= $p==$pageV?'bg-red-500':'' ?>"><?= $p ?></a>
<?php endfor; ?>
<?php if($pageV<$totalPageV): ?><a href="?page_v=<?= $pageV+1 ?>" class="px-4 py-2 border rounded-full">Next ›</a><?php endif; ?>
</div>
</div>

<!-- ================= RANKING ================= -->
<div class="bg-white/10 border border-white/20 rounded-xl p-6 mb-12">
<h3 class="font-bold text-red-500 mb-4">Peringkat Alternatif</h3>

<table class="w-full">
<thead class="bg-red-500/20">
<tr><th class="p-3 text-center">Rank</th><th class="p-3">Alternatif</th><th class="p-3 text-center">Nilai</th></tr>
</thead>
<tbody>
<?php $rank = ($pageR-1)*$limit+1; foreach($rankData as $i=>$val): ?>
<tr class="border-b border-white/10 <?= in_array($rank, [1,2,3]) ? 'bg-green-500/20' : '' ?>">
<td class="p-3 text-center font-bold"><?= $rank++ ?></td>
<td class="p-3"><?= $alt_name[$i] ?></td>
<td class="p-3 text-center"><?= $val ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="flex justify-center gap-2 mt-6">
<?php if($pageR>1): ?><a href="?page_r=<?= $pageR-1 ?>" class="px-4 py-2 border rounded-full">‹ Prev</a><?php endif; ?>
<?php for($p=1;$p<=$totalPageR;$p++): ?>
<a href="?page_r=<?= $p ?>" class="px-4 py-2 border rounded-full <?= $p==$pageR?'bg-red-500':'' ?>"><?= $p ?></a>
<?php endfor; ?>
<?php if($pageR<$totalPageR): ?><a href="?page_r=<?= $pageR+1 ?>" class="px-4 py-2 border rounded-full">Next ›</a><?php endif; ?>
</div>
</div>

<p class="text-center text-white/50 text-sm">© <?= $_SESSION['by'] ?></p>
</div>
</body>
</html>
