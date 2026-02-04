<?php
session_start();
include('configdb.php');

if (!isset($_SESSION['judul'])) $_SESSION['judul'] = "SPK League of Legends";
if (!isset($_SESSION['by'])) $_SESSION['by'] = "Irhas Maulana S";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $_SESSION['judul'].' - '.$_SESSION['by']; ?></title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- jQuery & DataTables -->
  <script src="ui/js/jquery-1.11.3.min.js"></script>
  <script src="ui/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="ui/css/datatables/dataTables.bootstrap.css">
  <link rel="stylesheet" href="ui/css/dark-table.css">
</head>

<body class="bg-black text-white font-sans antialiased">

<!-- NAVBAR -->
<nav class="fixed top-0 left-0 w-full z-50 bg-black/90 backdrop-blur border-b border-white/10">
  <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
    <a href="index.php" class="font-bold tracking-wider hover:text-red-500">
      <span class="text-red-500">SPK</span> WILD RIFT
    </a>
    <div class="hidden md:flex gap-8 text-sm font-semibold">
      <a href="kriteria.php" class="text-red-500">DATA KRITERIA</a>
      <a href="alternatif.php" class="hover:text-red-500">DATA ALTERNATIF</a>
      <a href="analisa.php" class="hover:text-red-500">ANALISA</a>
      <a href="perhitungan.php" class="hover:text-red-500">PERHITUNGAN</a>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="pt-32 px-6 max-w-7xl mx-auto">

  <!-- HEADER -->
  <div class="mb-10">
    <h1 class="text-3xl md:text-5xl font-black text-red-500 mb-4">DATA KRITERIA</h1>
    <p class="text-white/80 max-w-2xl">
      Halaman ini digunakan untuk mengelola kriteria penilaian menggunakan metode
      <b>Weighted Product</b>.
    </p>
  </div>

  <!-- INFO -->
  <div class="bg-white/10 border border-white/20 p-6 rounded-lg mb-10">
    <h4 class="font-bold mb-2 text-red-500">Informasi Penggunaan</h4>
    <ul class="list-disc list-inside text-sm space-y-1 text-white/80">
      <li>Skala kepentingan 1 (tidak penting) hingga 5 (sangat penting)</li>
      <li><b>Cost</b>: nilai kecil lebih baik</li>
      <li><b>Benefit</b>: nilai besar lebih baik</li>
    </ul>
  </div>

  <!-- FORM -->
  <div class="bg-white/10 border border-white/20 p-6 rounded-lg mb-12">
    <h3 class="font-bold text-red-500 mb-4">Tambah Kriteria</h3>
    <form method="post" action="tambah-kriteria.php" class="grid md:grid-cols-4 gap-4">
      <input type="text" name="kriteria" placeholder="Nama Kriteria" required class="px-4 py-2 bg-black/60 border border-white/20 rounded">
      <select name="kepentingan" required class="px-4 py-2 bg-black/60 border border-white/20 rounded">
        <option value="">Kepentingan</option>
        <option value="1">1 - Tidak Penting</option>
        <option value="2">2 - Kurang Penting</option>
        <option value="3">3 - Cukup Penting</option>
        <option value="4">4 - Penting</option>
        <option value="5">5 - Sangat Penting</option>
      </select>
      <select name="cost_benefit" required class="px-4 py-2 bg-black/60 border border-white/20 rounded">
        <option value="">Cost / Benefit</option>
        <option value="cost">Cost</option>
        <option value="benefit">Benefit</option>
      </select>
      <button class="bg-red-500 font-bold hover:scale-105 transition rounded">Tambah</button>
    </form>
  </div>

  <!-- TABLE -->
  <div class="bg-white/10 border border-white/20 rounded-lg p-6 shadow">
    <table id="example" class="table table-striped table-bordered w-full">
      <thead>
        <tr>
          <th>No</th>
          <th>Kriteria</th>
          <th>Kepentingan</th>
          <th>Cost / Benefit</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $kriteria = $mysqli->query("SELECT * FROM kriteria");
      $i = 1;
      while($row = $kriteria->fetch_assoc()){
        echo '<tr>';
        echo '<td>'.$i++.'</td>';
        echo '<td>'.ucwords($row['kriteria']).'</td>';
        echo '<td>
                <select class="update-data" data-id="'.$row['id_kriteria'].'" data-column="kepentingan">';
        for($x=1;$x<=5;$x++){
          $sel = $row['kepentingan']==$x?'selected':'';
          echo "<option value='$x' $sel>$x</option>";
        }
        echo '</select></td>';
        echo '<td>
                <select class="update-data" data-id="'.$row['id_kriteria'].'" data-column="cost_benefit">
                  <option value="cost" '.($row['cost_benefit']=='cost'?'selected':'').'>Cost</option>
                  <option value="benefit" '.($row['cost_benefit']=='benefit'?'selected':'').'>Benefit</option>
                </select>
              </td>';
        echo '<td><a href="edit-kriteria.php?id='.$row['id_kriteria'].'" class="text-red-500 font-semibold">Edit</a></td>';
        echo '</tr>';
      }
      ?>
      </tbody>
    </table>
  </div>

  <!-- BUTTON ANALISA -->
  <div id="btn-analisa-wrapper" class="hidden text-center mt-12">
    <a href="analisa.php"
       class="inline-block bg-red-500 hover:bg-red-600 transition font-bold px-12 py-4 rounded-full">
       Lanjut ke Analisa →
    </a>
  </div>

  <p class="text-center text-white/50 text-sm mt-12">
    © <?php echo $_SESSION['by']; ?>
  </p>
</div>

<!-- SCRIPT -->
<script>
$(document).ready(function(){

  var table = $('#example').DataTable({
    pageLength: 6,
    pagingType: "simple",
    searching: false,
    info: false,
    lengthChange: false,
    dom: 't<p>',
    language: {
      url: "ui/css/datatables/Indonesian.json"
    }
  });

  function checkLastPage(){
    var info = table.page.info();
    if(info.page === info.pages - 1){
      $('#btn-analisa-wrapper').removeClass('hidden');
    } else {
      $('#btn-analisa-wrapper').addClass('hidden');
    }
  }

  table.on('draw', checkLastPage);
  checkLastPage();

  $('.update-data').change(function(){
    $.post('update-kriteria.php',{
      id: $(this).data('id'),
      column: $(this).data('column'),
      value: $(this).val()
    });
  });

});
</script>

</body>
</html>
