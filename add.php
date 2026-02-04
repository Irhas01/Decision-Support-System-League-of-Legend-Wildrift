<?php
include('configdb.php');

// Ambil data dari form
$alternatif = $_POST['alternatif'];
$k1 = $_POST['k1'];
$k2 = $_POST['k2'];
$k3 = $_POST['k3'];
$k4 = $_POST['k4'];
$k5 = $_POST['k5'];
$k6 = $_POST['k6'];
$k7 = $_POST['k7'];
$k8 = $_POST['k8'];
$k9 = $_POST['k9'];
$k10 = $_POST['k10'];
$k11 = $_POST['k11'];

// Menyiapkan query dengan prepared statement
$query = "INSERT INTO alternatif (alternatif, k1, k2, k3, k4, k5, k6, k7, k8, k9, k10, k11) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Menyiapkan statement
$stmt = $mysqli->prepare($query);

if ($stmt === false) {
    die('Error preparing statement: ' . $mysqli->error);
}

// Mengikat parameter ke dalam prepared statement
$stmt->bind_param("ssssssssssss", $alternatif, $k1, $k2, $k3, $k4, $k5, $k6, $k7, $k8, $k9, $k10, $k11);

// Menjalankan statement
if ($stmt->execute()) {
    header('Location: alternatif.php');
} else {
    echo "Gagal menambahkan alternatif: " . $stmt->error;
}

// Menutup statement
$stmt->close();
?>
