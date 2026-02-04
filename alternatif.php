<?php
session_start();
include('configdb.php');

if (!isset($_SESSION['judul'])) $_SESSION['judul'] = "SPK League of Legends";
if (!isset($_SESSION['by'])) $_SESSION['by'] = "Irhas Maulana S";

/* ====== PAGINATION ====== */
$limit = 10;
$page  = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
$offset = ($page-1)*$limit;

/* ====== KRITERIA ====== */
$kriteria = [];
$qk = $mysqli->query("SELECT * FROM kriteria");
while($row = $qk->fetch_assoc()){
  $kriteria[] = $row['kriteria'];
}

/* ====== ALTERNATIF ====== */
$totalData = $mysqli->query("SELECT COUNT(*) AS total FROM alternatif")
             ->fetch_assoc()['total'];
$totalPage = ceil($totalData/$limit);

$alternatif = $mysqli->query(
  "SELECT * FROM alternatif LIMIT $offset,$limit"
);

/* ====== TRUNCATE FUNCTION ====== */
function truncate($text, $max=7){
    return strlen($text) > $max ? substr($text,0,$max).'...' : $text;
}
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
  <h1 class="text-4xl md:text-5xl font-black text-red-500 mb-4">DATA ALTERNATIF</h1>
  <p class="text-white/80 max-w-2xl">
    Data alternatif yang akan dinilai berdasarkan kriteria <b>Stat Karakter</b>
  </p>
</div>

<!-- ACTION -->
<div class="mb-6">
  <a href="add-alternatif.php"
     class="inline-block bg-red-500 hover:bg-red-600 transition
            font-bold px-6 py-3 rounded-full">
    + Tambah Alternatif
  </a>
</div>

<!-- TABLE -->
<div class="bg-white/10 border border-white/20 rounded-xl p-6 mb-12 overflow-x-auto">
<table class="w-full text-sm border-collapse">
<thead class="bg-red-500/20">
<tr>
  <th class="p-3 w-12">No</th>
  <th class="p-3 text-left min-w-[100px] max-w-[150px]">Alternatif</th>
  <?php foreach($kriteria as $k): ?>
    <th class="p-3 min-w-[90px] max-w-[120px] text-center truncate"><?= truncate(ucwords($k)) ?></th>
  <?php endforeach; ?>
  <th class="p-3 min-w-[150px] text-center">Aksi</th>
</tr>
</thead>

<tbody>
<?php
$no = $offset+1;
while($row = $alternatif->fetch_assoc()):
?>
<tr class="border-b border-white/10 hover:bg-white/5">
<td class="p-3 text-center"><?= $no++ ?></td>
<td class="p-3 font-semibold max-w-[150px] truncate"><?= truncate(ucwords($row['alternatif'])) ?></td>

<?php for($i=1;$i<=count($kriteria);$i++): ?>
<td class="p-3 text-center max-w-[120px] truncate"><?= truncate($row["k$i"]) ?></td>
<?php endfor; ?>

<td class="p-3 text-center">
  <a href="edit-alternatif.php?id=<?= $row['id_alternatif'] ?>" 
     class="text-red-500 font-semibold hover:underline">Edit</a>
  |
  <a href="del.php?id=<?= $row['id_alternatif'] ?>" 
     onclick="return confirm('Hapus alternatif <?= $row['alternatif'] ?> ?')" 
     class="text-red-500 font-semibold hover:underline">Hapus</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<!-- PAGINATION -->
<div class="flex justify-center gap-2 mt-8">
<?php if($page>1): ?>
<a href="?page=<?= $page-1 ?>"
   class="px-4 py-2 border rounded-full hover:bg-white/10">
‹ Prev
</a>
<?php endif; ?>

<?php for($p=1;$p<=$totalPage;$p++): ?>
<a href="?page=<?= $p ?>"
   class="px-4 py-2 border rounded-full <?= $p==$page?'bg-red-500':'' ?>">
<?= $p ?>
</a>
<?php endfor; ?>

<?php if($page<$totalPage): ?>
<a href="?page=<?= $page+1 ?>"
   class="px-4 py-2 border rounded-full hover:bg-white/10">
Next ›
</a>
<?php endif; ?>
</div>
</div>

<p class="text-center text-white/50 text-sm">
© <?= $_SESSION['by']; ?>
</p>

</body>
</html>
