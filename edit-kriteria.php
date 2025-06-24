<?php
session_start();
include('configdb.php');

// Ambil ID dari URL
$id_kriteria = $_GET['id'];

// Query untuk mengambil data kriteria berdasarkan ID
$result = $mysqli->query("SELECT * FROM kriteria WHERE id_kriteria = $id_kriteria");
if (!$result) {
    echo "Error: " . $mysqli->connect_error;
    exit();
}

// Ambil data kriteria yang ingin diedit
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kriteria</title>
    <link href="ui/css/cerulean.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- Static navbar -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#"><?php echo $_SESSION['judul'];?></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="kriteria.php">Data Kriteria</a></li>
                    <li><a href="alternatif.php">Data Alternatif</a></li>
                    <li><a href="analisa.php">Analisa</a></li>
                    <li><a href="perhitungan.php">Perhitungan</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Edit Kriteria</div>
            <div class="panel-body">
                <form method="POST" action="edit-kriteria.php?id=<?php echo $id_kriteria; ?>" class="form-horizontal">
                    <div class="form-group">
                        <label for="kriteria" class="col-sm-2 control-label">Kriteria</label>
                        <div class="col-sm-10">
                            <input type="text" name="kriteria" class="form-control" value="<?php echo $row['kriteria']; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="kepentingan" class="col-sm-2 control-label">Kepentingan</label>
                        <div class="col-sm-10">
                            <select name="kepentingan" class="form-control" required>
                                <option value="1" <?php if ($row['kepentingan'] == 1) echo 'selected'; ?>>1 - Tidak Penting</option>
                                <option value="2" <?php if ($row['kepentingan'] == 2) echo 'selected'; ?>>2 - Kurang Penting</option>
                                <option value="3" <?php if ($row['kepentingan'] == 3) echo 'selected'; ?>>3 - Cukup Penting</option>
                                <option value="4" <?php if ($row['kepentingan'] == 4) echo 'selected'; ?>>4 - Penting</option>
                                <option value="5" <?php if ($row['kepentingan'] == 5) echo 'selected'; ?>>5 - Sangat Penting</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cost_benefit" class="col-sm-2 control-label">Cost / Benefit</label>
                        <div class="col-sm-10">
                            <select name="cost_benefit" class="form-control" required>
                                <option value="cost" <?php if ($row['cost_benefit'] == 'cost') echo 'selected'; ?>>Cost</option>
                                <option value="benefit" <?php if ($row['cost_benefit'] == 'benefit') echo 'selected'; ?>>Benefit</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Update Kriteria</button>
                            <a href="kriteria.php" class="btn btn-warning">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="ui/js/jquery-1.10.2.min.js"></script>
    <script src="ui/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Proses pembaruan data kriteria
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $kriteria = $mysqli->real_escape_string($_POST['kriteria']);
    $kepentingan = $mysqli->real_escape_string($_POST['kepentingan']);
    $cost_benefit = $mysqli->real_escape_string($_POST['cost_benefit']);

    // Query untuk memperbarui data kriteria
    $update_query = "UPDATE kriteria SET kriteria = '$kriteria', kepentingan = '$kepentingan', cost_benefit = '$cost_benefit' WHERE id_kriteria = $id_kriteria";

    if ($mysqli->query($update_query)) {
        $_SESSION['success'] = "Data kriteria berhasil diperbarui.";
        header('Location: kriteria.php');  // Arahkan ke halaman data kriteria setelah update
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui data kriteria: " . $mysqli->error;
        header("Location: edit-kriteria.php?id=$id_kriteria");
        exit();
    }
}
?>
