<?php
session_start();
include('configdb.php');

// Ambil ID dari URL
$id_kriteria = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_kriteria <= 0) {
    $_SESSION['error'] = "ID kriteria tidak valid.";
    header('Location: kriteria.php');
    exit();
}

// Ambil data kriteria berdasarkan ID
$stmt = $mysqli->prepare("SELECT * FROM kriteria WHERE id_kriteria = ?");
$stmt->bind_param("i", $id_kriteria);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    $_SESSION['error'] = "Data kriteria tidak ditemukan.";
    header('Location: kriteria.php');
    exit();
}

// Proses pembaruan data kriteria
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kriteria = $_POST['kriteria'];
    $kepentingan = $_POST['kepentingan'];
    $cost_benefit = $_POST['cost_benefit'];

    $update = $mysqli->prepare("UPDATE kriteria SET kriteria=?, kepentingan=?, cost_benefit=? WHERE id_kriteria=?");
    $update->bind_param("ssis", $kriteria, $kepentingan, $cost_benefit, $id_kriteria);

    if ($update->execute()) {
        $_SESSION['success'] = "Data kriteria berhasil diperbarui.";
        header('Location: kriteria.php');
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui data kriteria: " . $update->error;
        header("Location: edit-kriteria.php?id=$id_kriteria");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Kriteria - <?= $_SESSION['judul']; ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white font-sans antialiased">

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

<!-- Konten Utama -->
<section class="pt-28 pb-16 px-6">
    <div class="max-w-3xl mx-auto bg-gray-900/80 rounded-2xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-red-500 mb-6">Edit Kriteria</h1>

        <!-- Alert -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-4 p-3 bg-red-600 text-white rounded">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-4 p-3 bg-green-600 text-white rounded">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="edit-kriteria.php?id=<?= $id_kriteria; ?>" class="space-y-6"
              onsubmit="return confirm('Apakah Anda yakin ingin memperbarui kriteria ini?')">
            
            <!-- Nama Kriteria -->
            <div>
                <label for="kriteria" class="block mb-2 font-semibold">Kriteria</label>
                <input type="text" name="kriteria" id="kriteria"
                       value="<?= htmlspecialchars($row['kriteria']); ?>"
                       class="w-full px-4 py-2 rounded-lg text-black" required>
            </div>

            <!-- Kepentingan -->
            <div>
                <label for="kepentingan" class="block mb-2 font-semibold">Kepentingan</label>
                <select name="kepentingan" id="kepentingan" class="w-full px-4 py-2 rounded-lg text-black" required>
                    <?php for ($x = 1; $x <= 5; $x++): ?>
                        <option value="<?= $x; ?>" <?= $row['kepentingan'] == $x ? 'selected' : ''; ?>>
                            <?= $x; ?> - 
                            <?php
                            switch ($x) {
                                case 1: echo "Tidak Penting"; break;
                                case 2: echo "Kurang Penting"; break;
                                case 3: echo "Cukup Penting"; break;
                                case 4: echo "Penting"; break;
                                case 5: echo "Sangat Penting"; break;
                            }
                            ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Cost / Benefit -->
            <div>
                <label for="cost_benefit" class="block mb-2 font-semibold">Cost / Benefit</label>
                <select name="cost_benefit" id="cost_benefit" class="w-full px-4 py-2 rounded-lg text-black" required>
                    <option value="cost" <?= $row['cost_benefit'] == 'cost' ? 'selected' : ''; ?>>Cost</option>
                    <option value="benefit" <?= $row['cost_benefit'] == 'benefit' ? 'selected' : ''; ?>>Benefit</option>
                </select>
            </div>

            <!-- Tombol -->
            <div class="flex items-center gap-4">
                <button type="submit" class="bg-red-500 px-6 py-3 rounded-lg font-bold hover:bg-red-600 transition">
                    Update Kriteria
                </button>
                <a href="kriteria.php" class="bg-gray-600 px-6 py-3 rounded-lg font-bold hover:bg-gray-700 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</section>

</body>
</html>
