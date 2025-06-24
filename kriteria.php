<?php
session_start();
include('configdb.php');

// Pastikan session judul dan by tidak kosong
if (!isset($_SESSION['judul'])) {
    $_SESSION['judul'] = "SPK League of Legends";
}
if (!isset($_SESSION['by'])) {
    $_SESSION['by'] = "Irhas Maulana S";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION['judul'] . " - " . $_SESSION['by']; ?></title>

    <!-- Bootstrap -->
    <link href="ui/css/cerulean.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="ui/css/datatables/dataTables.bootstrap.css">

    <!-- jQuery dan DataTables -->
    <script src="ui/js/jquery-1.11.3.min.js"></script>  
    <script src="ui/js/jquery.dataTables.min.js"></script>
    <script src="ui/js/dataTables.bootstrap.min.js"></script>
</head>

<body>
<div id="wrapper">
    <!-- Navbar -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><?php echo $_SESSION['judul']; ?></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li class="active"><a href="kriteria.php">Data Kriteria</a></li>
                    <li><a href="alternatif.php">Data Alternatif</a></li>
                    <li><a href="analisa.php">Analisa</a></li>
                    <li><a href="perhitungan.php">Perhitungan</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Data Kriteria</div>

                        <!-- Info Penggunaan -->
                        <div class="alert alert-info">
                            <h4><i class="fa fa-info-circle"></i> Informasi Penggunaan</h4>
                            <p>Silakan isi setiap nilai kepentingan sesuai dengan kebutuhan Anda.</p>
                            <ul>
                                <li>Gunakan angka antara <strong>1</strong> (tidak terlalu penting) hingga <strong>5</strong> (sangat penting).</li>
                                <li>Pilih <strong>Cost</strong> jika nilai lebih kecil lebih baik.</li>
                                <li>Pilih <strong>Benefit</strong> jika nilai lebih besar lebih baik.</li>
                            </ul>
                        </div>
                        
                        <!-- Form Tambah Kriteria -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">Tambah Kriteria Baru</div>
                            <div class="panel-body">
                                <form method="post" action="tambah-kriteria.php" class="form-inline">
                                    <div class="form-group">
                                        <label for="kriteria" class="sr-only">Kriteria</label>
                                        <input type="text" name="kriteria" id="kriteria" class="form-control" placeholder="Nama Kriteria" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kepentingan" class="sr-only">Kepentingan</label>
                                        <select name="kepentingan" id="kepentingan" class="form-control" required>
                                            <option value="">Pilih Kepentingan</option>
                                            <option value="1">1 - Tidak Penting</option>
                                            <option value="2">2 - Kurang Penting</option>
                                            <option value="3">3 - Cukup Penting</option>
                                            <option value="4">4 - Penting</option>
                                            <option value="5">5 - Sangat Penting</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="cost_benefit" class="sr-only">Cost / Benefit</label>
                                        <select name="cost_benefit" id="cost_benefit" class="form-control" required>
                                            <option value="">Pilih Cost / Benefit</option>
                                            <option value="cost">Cost</option>
                                            <option value="benefit">Benefit</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Tambah</button>
                                </form>
                            </div>
                        </div>


                        <div class="panel-body table-responsive">
                            <table id="example" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kriteria</th>
                                    <th>Kepentingan</th>
                                    <th>Cost / Benefit</th>
                                    <th>Opsi</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $kriteria = $mysqli->query("SELECT * FROM kriteria");
                                if (!$kriteria) {
                                    echo "<tr><td colspan='5'>Error: " . $mysqli->connect_error . "</td></tr>";
                                    exit();
                                }
                                $i = 1;
                                while ($row = $kriteria->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td>" . ucwords($row["kriteria"]) . "</td>";

                                    // Dropdown Kepentingan
                                    echo '<td>
                                        <select class="form-control update-data" data-id="' . $row["id_kriteria"] . '" data-column="kepentingan">
                                            <option value="1" ' . ($row["kepentingan"] == '1' ? "selected" : "") . '>1 - Tidak Penting</option>
                                            <option value="2" ' . ($row["kepentingan"] == '2' ? "selected" : "") . '>2 - Kurang Penting</option>
                                            <option value="3" ' . ($row["kepentingan"] == '3' ? "selected" : "") . '>3 - Cukup Penting</option>
                                            <option value="4" ' . ($row["kepentingan"] == '4' ? "selected" : "") . '>4 - Penting</option>
                                            <option value="5" ' . ($row["kepentingan"] == '5' ? "selected" : "") . '>5 - Sangat Penting</option>
                                        </select>
                                    </td>';

                                    // Dropdown Cost/Benefit
                                    echo '<td>
                                    <select class="form-control update-data" data-id="' . $row["id_kriteria"] . '" data-column="cost_benefit">
                                        <option value="cost" ' . ($row["cost_benefit"] == 'cost' ? "selected" : "") . '>Cost</option>
                                        <option value="benefit" ' . ($row["cost_benefit"] == 'benefit' ? "selected" : "") . '>Benefit</option>
                                    </select>
                                    </td>';

                                    // Kolom Opsi dengan tombol Edit di tengah
                                    echo '<td class="text-center align-middle">
                                    <a href="edit-kriteria.php?id=' . $row["id_kriteria"] . '" class="btn btn-primary btn-sm">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    </td>';
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer text-primary"><?php echo $_SESSION['by']; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script src="ui/js/bootstrap.min.js"></script>
<script src="ui/js/bootswatch.js"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable({
        "language": {
            "url": "ui/css/datatables/Indonesian.json"
        }
    });

    $('.update-data').change(function() {
        var id = $(this).data('id');
        var column = $(this).data('column');
        var value = $(this).val();

        $.ajax({
            url: 'update-kriteria.php',
            type: 'POST',
            data: { id: id, column: column, value: value },
            success: function(response) {
                console.log('Data berhasil diperbarui:', response);
            },
            error: function(xhr, status, error) {
                console.error('Gagal memperbarui data:', error);
                alert('Gagal memperbarui data.');
            }
        });
    });
});
</script>

</body>
</html>