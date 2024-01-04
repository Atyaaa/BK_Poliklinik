<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['id_dokter'])) {
    // Jika id_dokter belum diset (mungkin dokter belum login), redirect ke halaman login dokter
    header("Location: loginUser.php");
    exit;
}

// Dapatkan id_dokter dari sesi
$id_dokter = $_SESSION['id_dokter'];

// Sekarang, Anda dapat menggunakannya saat menyimpan jadwal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses menyimpan jadwal (termasuk id_dokter)
    $id_dokter_posted = $_POST['id_dokter'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Pastikan id_dokter yang dipost sama dengan id_dokter dari sesi
    if ($id_dokter_posted == $id_dokter) {
        // Simpan ke database (sesuaikan dengan logika aplikasi Anda)
        $query = "INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai) VALUES ('$id_dokter', '$hari', '$jam_mulai', '$jam_selesai')";
        // Eksekusi query
        $result = $mysqli->query($query);

        if ($result) {
            $pesan = "Jadwal berhasil disimpan.";
        } else {
            $pesan = "Terjadi kesalahan saat menyimpan jadwal.";
        }
    } else {
        $pesan = "ID Dokter tidak valid.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Dokter</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <main role="main" class="container">
        <h2>Halaman Jadwal Dokter</h2>
        <p>Selamat datang, <?php echo $_SESSION['username']; ?>! (ID Dokter: <?php echo $id_dokter; ?>)</p>

        <!-- Formulir untuk memasukkan jadwal -->
        <form method="POST" action="">
            <div class="form-group">
                
                <label for="hari">Hari:</label>
                <select name="hari" required>
                    <?php
                    // Mendapatkan nilai-nilai enum dari database
                    $result = $mysqli->query("SHOW COLUMNS FROM jadwal_periksa LIKE 'hari'");
                    $enum_str = $result->fetch_assoc()['Type'];
                    preg_match('/enum\((.*)\)$/', $enum_str, $matches);
                    $enum_values = explode(',', $matches[1]);

                    // Menampilkan nilai-nilai enum dalam dropdown
                    foreach ($enum_values as $value) {
                        $trimmed_value = trim($value, "'");
                        echo "<option value='$trimmed_value'>$trimmed_value</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jam_mulai">Jam Mulai:</label>
                <input type="time" name="jam_mulai" required>
            </div>
            <div class="form-group">
                <label for="jam_selesai">Jam Selesai:</label>
                <input type="time" name="jam_selesai" required>
            </div>

            <input type="hidden" name="id_dokter" value="<?php echo $id_dokter; ?>">

            <div class="form-group">
                <button type="submit" name="simpan">Simpan Jadwal</button>
            </div>
        </form>


        <?php
        // Tampilkan pesan sukses atau gagal
        if (isset($pesan)) {
            echo "<p>$pesan</p>";
        }
        ?>
    </main>
</body>

</html>
