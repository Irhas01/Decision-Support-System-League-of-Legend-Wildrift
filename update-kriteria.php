<?php
include('configdb.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $column = $_POST['column'];
    $value = $_POST['value'];

    // Validasi kolom yang bisa diubah
    $allowed_columns = ['kepentingan', 'cost_benefit'];
    if (!in_array($column, $allowed_columns)) {
        echo "Kolom tidak valid!";
        exit();
    }

    // Update dengan prepared statement
    $stmt = $mysqli->prepare("UPDATE kriteria SET $column = ? WHERE id_kriteria = ?");
    $stmt->bind_param("si", $value, $id);
    
    if ($stmt->execute()) {
        echo "Sukses";
    } else {
        echo "Gagal: " . $stmt->error;
    }
    
    $stmt->close();
}
?>