<?php
session_start();
include('configdb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data kriteria dari form
    $kriteria = $mysqli->real_escape_string(trim($_POST['kriteria']));
    $kepentingan = $mysqli->real_escape_string($_POST['kepentingan']);
    $cost_benefit = $mysqli->real_escape_string($_POST['cost_benefit']);

    // Query untuk menambah kriteria baru ke dalam tabel kriteria
    $query = "INSERT INTO kriteria (kriteria, kepentingan, cost_benefit) VALUES ('$kriteria', '$kepentingan', '$cost_benefit')";

    if ($mysqli->query($query)) {
        $_SESSION['success'] = "Kriteria baru berhasil ditambahkan.";

        // Menambahkan kolom baru untuk kriteria baru ke dalam tabel alternatif
        // Menggunakan query ALTER TABLE untuk menambah kolom baru untuk kriteria yang baru ditambahkan
        $last_kriteria_id = $mysqli->insert_id; // Ambil ID kriteria yang baru ditambahkan
        $new_column_name = "k" . $last_kriteria_id; // Membuat nama kolom k1, k2, dst sesuai ID

        $alter_query = "ALTER TABLE alternatif ADD COLUMN $new_column_name INT DEFAULT 0"; // Nilai default = 0
        if ($mysqli->query($alter_query)) {
            $_SESSION['success'] = "Kolom baru untuk alternatif berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Gagal menambahkan kolom baru untuk alternatif: " . $mysqli->error;
        }

        header("Location: kriteria.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal menambahkan data kriteria: " . $mysqli->error;
        header("Location: kriteria.php");
        exit();
    }
}
?>

<!-- Form untuk menambah Kriteria -->
<form method="post">
    <label for="kriteria">Nama Kriteria:</label>
    <input type="text" name="kriteria" required><br>

    <label for="kepentingan">Kepentingan:</label>
    <select name="kepentingan" required>
        <option value="1">1 - Tidak Penting</option>
        <option value="2">2 - Kurang Penting</option>
        <option value="3">3 - Cukup Penting</option>
        <option value="4">4 - Penting</option>
        <option value="5">5 - Sangat Penting</option>
    </select><br>

    <label for="cost_benefit">Cost / Benefit:</label>
    <select name="cost_benefit" required>
        <option value="cost">Cost</option>
        <option value="benefit">Benefit</option>
    </select><br>

    <button type="submit">Tambah Kriteria</button>
</form>
