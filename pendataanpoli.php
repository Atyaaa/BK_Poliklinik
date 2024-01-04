<?php
include('koneksi.php');

// Start the session
if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['no_rm'])) {
    $nomor_rm = $_GET['no_rm'];
    header("Location: daftarpoli.php");
    exit;
}

// Get id_pasien and id_jadwal from the query parameters
if (isset($_GET['id_pasien']) && isset($_GET['id_jadwal'])) {
    $id_pasien = $_GET['id_pasien'];
    $id_jadwal = $_GET['id_jadwal'];

    echo "ID Pasien: " . $id_pasien;
    echo "  ID Jadwal: " . $id_jadwal;

    // Query to get patient's information and scheduled appointment
    $queryGetPasien = "SELECT nama, no_rm FROM pasien WHERE id = '$id_pasien'";
    $queryGetJadwal = "SELECT jadwal_periksa.hari, jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai, poli.nama_poli, dokter.nama
        FROM jadwal_periksa
        INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id
        INNER JOIN poli ON dokter.id_poli = poli.id
        WHERE jadwal_periksa.id = '$id_jadwal'";
    $queryTotalDaftarPoli = "SELECT COUNT(*) AS total FROM daftar_poli";
    
    $resultGetPasien = $mysqli->query($queryGetPasien);
    $resultGetJadwal = $mysqli->query($queryGetJadwal);
    $resultTotalDaftarPoli = $mysqli->query($queryTotalDaftarPoli);

    if ($resultGetPasien && $resultGetJadwal) {
        $rowPasien = $resultGetPasien->fetch_assoc();
        $rowJadwal = $resultGetJadwal->fetch_assoc();
        $rowTotalDaftarPoli = $resultTotalDaftarPoli->fetch_assoc();

        $nama_pasien = $rowPasien['nama'];
        echo "  Nama pasien : " . $nama_pasien;
        $no_rm = $rowPasien['no_rm'];
        $hari = $rowJadwal['hari'];
        $jam_mulai = $rowJadwal['jam_mulai'];
        $jam_selesai = $rowJadwal['jam_selesai'];
        $nama_poli = $rowJadwal['nama_poli'];
        $nama_dokter = $rowJadwal['nama'];
        $no_antrian = $rowTotalDaftarPoli['total'] + 1;
    } else {
        echo "Terjadi kesalahan saat mengambil data: " . $mysqli->error;
        exit;
    }
} else {
    echo "Parameter id_pasien dan id_jadwal tidak valid.";
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the entered keluhan
    $keluhan = $mysqli->real_escape_string($_POST['keluhan']);

    // Insert data into daftar_poli table
    $queryInsertDaftarPoli = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian) 
                              VALUES ('$id_pasien', '$id_jadwal', '$keluhan', $no_antrian)";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($mysqli->query($queryInsertDaftarPoli)) {
            // Display a JavaScript alert as a confirmation popup
            echo "<script>";
            echo "alert('Data berhasil disimpan.');";
            echo "window.location.href = 'daftarpoli.php';";
            echo "</script>";
            // You can also perform additional actions or updates on the same page if needed
        } else {
            echo "Terjadi kesalahan saat memasukkan data ke dalam tabel daftar_poli: " . $mysqli->error;
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendataan Poli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistem Informasi Poliklinik</a>
            <!-- Add other navigation links if needed -->
        </div>
    </nav>

    <main role="main" class="container">
    <link rel="stylesheet" type="text/css" href="style.css">    
        <h2>Pendataan Poli</h2>
        <p>Silahkan isi formulir pendataan poli di bawah ini:</p>  

        <form method="POST" action="pendataanpoli.php?id_pasien=<?= $id_pasien ?>&id_jadwal=<?= $id_jadwal ?>">
            <div class="mb-3">
                <label for="nama">Nama Pasien:</label>
                <input type="text" class="form-control" id="nama" value="<?= $nama_pasien ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="no_rm">Nomor Rekam Medis (no_rm):</label>
                <input type="text" class="form-control" id="no_rm" value="<?= $no_rm ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="jadwal">Jadwal Poli:</label>
                <input type="text" class="form-control" id="jadwal" value="<?= $hari ?>, <?= $jam_mulai ?> - <?= $jam_selesai ?>, <?= $nama_poli ?> - Dr. <?= $nama_dokter ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="keluhan">Keluhan:</label>
                <textarea class="form-control" id="keluhan" name="keluhan" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
