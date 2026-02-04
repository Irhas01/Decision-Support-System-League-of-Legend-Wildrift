<?php
session_start();
include('configdb.php');

/* ====== AMBIL KRITERIA ====== */
$kriteria = [];
$qk = $mysqli->query("SELECT * FROM kriteria");
while($row = $qk->fetch_assoc()){
    $kriteria[] = $row['kriteria'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alternatif = $_POST['alternatif'];

    // Siapkan data nilai kriteria dari form
    $nilai = [];
    foreach($kriteria as $k) {
        $nilai[$k] = $_POST[$k];
    }

    // Siapkan query INSERT dinamis
    $cols = "alternatif, " . implode(", ", array_keys($nilai));
    $placeholders = implode(", ", array_fill(0, count($nilai)+1, "?")); // +1 untuk alternatif
    $query = "INSERT INTO alternatif ($cols) VALUES ($placeholders)";

    $stmt = $mysqli->prepare($query);
    if ($stmt === false) die('Error preparing statement: ' . $mysqli->error);

    // Bind semua parameter (semua string)
    $types = str_repeat("s", count($nilai)+1);
    $params = array_merge([$alternatif], array_values($nilai));
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        header('Location: alternatif.php');
        exit;
    } else {
        $error = "Gagal menambahkan alternatif: " . $stmt->error;
    }

    $stmt->close();
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

<?php include 'navbar.php'; ?>

<div class="pt-28 px-6 max-w-3xl mx-auto">

<div class="mb-10">
  <h1 class="text-4xl md:text-5xl font-black text-red-500 mb-4">Tambah Alternatif</h1>
  <p class="text-white/80 max-w-2xl">
    Masukkan data alternatif beserta nilai tiap kriteria
  </p>
</div>

<?php if(isset($error)): ?>
<p class="mb-4 text-red-500 font-bold"><?= $error ?></p>
<?php endif; ?>

<form action="" method="post" class="bg-white/10 border border-white/20 rounded-xl p-6 space-y-4">
  <div>
    <label class="block mb-1 font-semibold">Nama Alternatif</label>
    <input type="text" name="alternatif" required 
           class="w-full px-4 py-2 rounded bg-white/10 border border-white/20 focus:outline-none focus:border-red-500">
  </div>

  <?php foreach($kriteria as $k): ?>
  <div>
    <label class="block mb-1 font-semibold"><?= ucwords($k) ?></label>
    <input type="text" name="<?= $k ?>" required 
           class="w-full px-4 py-2 rounded bg-white/10 border border-white/20 focus:outline-none focus:border-red-500">
  </div>
  <?php endforeach; ?>

  <div class="flex gap-4 mt-4">
    <button type="submit" 
            class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-full font-bold transition">
      Tambah
    </button>
    <a href="alternatif.php" 
       class="bg-white/10 hover:bg-white/20 px-6 py-3 rounded-full font-bold transition">
      Batal
    </a>
  </div>
</form>

</div>

<p class="text-center text-white/50 text-sm mt-12">
© <?= $_SESSION['by']; ?>
</p>

</body>
</html>
