<?php
include('koneksi.php');

// Start the session
if (!isset($_SESSION)) {
    session_start();
}

// Check if the form is submitted and if "no_rm" is set in $_POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the logout button is clicked
    if (isset($_POST['logout'])) {
        // Remove no_rm from the session
        unset($_SESSION['no_rm']);
        // Redirect to daftarpoli.php
        header("Location: daftarpoli.php");
        exit;
    }

    // Check if the login form is submitted
    if (isset($_POST['no_rm'])) {
        // Retrieve the entered no_rm
        $nomor_rm = $_POST['no_rm'];

        // Query to validate no_rm
        $queryValidateNoRM = "SELECT nama FROM pasien WHERE no_rm = '$nomor_rm'";
        $resultValidateNoRM = $mysqli->query($queryValidateNoRM);

        if ($resultValidateNoRM && $resultValidateNoRM->num_rows == 1) {
            // Set no_rm in the session
            $_SESSION['no_rm'] = $nomor_rm;
        } else {
            // Display a pop-up window with an error message
            echo "<script>";
            echo "alert('Nomor Rekam Medis tidak valid.');";
            echo "window.location.href = 'daftarpoli.php';";
            echo "</script>";
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
    <title>BK Poliklinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistem Informasi Poliklinik</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="daftarpoli.php">Daftar Poli</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=registerUser">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=loginUser">Login</a>
                        </li>
                    </ul>
            </div>
        </div>
    </nav>

    <main role="main" class="container">
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php
    // Check if the user is logged in
    if (isset($_SESSION['no_rm'])) {
        $nomor_rm = $_SESSION['no_rm'];

        // Query to get patient's name
        $queryGetNama = "SELECT nama FROM pasien WHERE no_rm = '$nomor_rm'";
        $queryGetId = "SELECT id FROM pasien WHERE no_rm = '$nomor_rm'";
        $resultGetNama = $mysqli->query($queryGetNama);
        $resultGetId = $mysqli->query($queryGetId);

        if ($resultGetNama) {
            $rowNama = $resultGetNama->fetch_assoc();
            $rowId = $resultGetId->fetch_assoc();
            $nama_pasien = $rowNama['nama'];
            $id_pasien = $rowId['id'];

            // Display patient's information
            echo "<h2>Selamat Datang Dihalaman Daftar Poli</h2>";
            echo "<p>Halloo, " . $nama_pasien . "!</p>";
            echo "<p>Nomor Rekam medis mu adalah : " . $nomor_rm . "</p>";

            // Query to get the list of scheduled appointments
            $queryDaftarPoli = "SELECT jadwal_periksa.id, jadwal_periksa.hari, jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai, poli.nama_poli, dokter.nama
            FROM jadwal_periksa
            INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id
            INNER JOIN poli ON dokter.id_poli = poli.id";
            $resultDaftarPoli = $mysqli->query($queryDaftarPoli);

            if ($resultDaftarPoli) {
                // Display the list of scheduled appointments as a table within a Bootstrap container
                echo "<div class='container'>";
                echo "<h3 class='mt-4'>Daftar Poliklinik Tersedia:</h3>";
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered'>";
                echo "<thead>
                        <tr>
                            <th scope='col'>Hari</th>
                            <th scope='col'>Dokter</th>
                            <th scope='col'>Poli</th>
                            <th scope='col'>Jam</th>";
                // Check if the user is logged in
                if (isset($_SESSION['no_rm'])) {
                    echo "<th scope='col'>Daftar</th>";
                }
                echo "</tr>
                      </thead>";

                echo "<tbody>";

                while ($row = $resultDaftarPoli->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['hari'] . "</td>";
                    echo "<td>" . $row['nama'] . "</td>";
                    echo "<td>" . $row['nama_poli'] . "</td>";
                    echo "<td>" . $row['jam_mulai'] . " - " . $row['jam_selesai'] . "</td>";
                    // Check if the user is logged in
                    if (isset($_SESSION['no_rm'])) {
                        echo "<td><a href='pendataanpoli.php?id_pasien=" . $id_pasien . "&id_jadwal=" . $row['id'] . "' class='btn btn-primary'>Pilih</a></td>";
                    }
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                echo "</div>"; // table-responsive
                echo "</div>"; // container
            } else {
                echo "Terjadi kesalahan saat mengambil data daftar poliklinik: " . $mysqli->error;
            }
        } else {
            echo "Terjadi kesalahan saat mengambil data nama pasien: " . $mysqli->error;
        }
    } else {
        // If no_rm is not set in the session, display an error message or redirect to the login page
        echo "<h2>INI HALAMAN DAFTAR POLI NGAB</h2>";
        echo "<p>Anda dapat melihat daftar poliklinik tanpa login. Silahkan login untuk mendaftar poliklinik.</p>";

        // Query to get the list of scheduled appointments
        $queryDaftarPoli = "SELECT jadwal_periksa.id, jadwal_periksa.hari, jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai, poli.nama_poli, dokter.nama
        FROM jadwal_periksa
        INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id
        INNER JOIN poli ON dokter.id_poli = poli.id";
        $resultDaftarPoli = $mysqli->query($queryDaftarPoli);

        if ($resultDaftarPoli) {
            // Display the list of scheduled appointments as a table within a Bootstrap container
            echo "<div class='container'>";
            echo "<h3 class='mt-4'>Daftar Poliklinik Tersedia:</h3>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered'>";
            echo "<thead>
                    <tr>
                        <th scope='col'>Hari</th>
                        <th scope='col'>Dokter</th>
                        <th scope='col'>Poli</th>
                        <th scope='col'>Jam</th>";
            echo "</tr>
                  </thead>";

            echo "<tbody>";

            while ($row = $resultDaftarPoli->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['hari'] . "</td>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td>" . $row['nama_poli'] . "</td>";
                echo "<td>" . $row['jam_mulai'] . " - " . $row['jam_selesai'] . "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "</div>"; // table-responsive
            echo "</div>"; // container
        } else {
            echo "Terjadi kesalahan saat mengambil data daftar poliklinik: " . $mysqli->error;
        }
    }
    ?>
</main>


    <div class="container mt-5">
        <div class="row justify-content-end">
            <div class="col-12 col-sm-8 col-md-6">
                <div class="card p-3" id="form1">
                    <div class="form-data">
                        <?php
                        // Display the login form or logout button
                        if (!isset($_SESSION['no_rm'])) {
                            echo "<h4 class='text-center mb-3'>Login Pasien</h4>";
                            echo "<form method='POST' action='daftarpoli.php'>";
                            echo "<div class='mb-3'>";
                            echo "<input type='text' name='no_rm' class='form-control' required placeholder='Nomor Rekam Medis'>";
                            echo "</div>";
                            echo "<div class='text-center'>";
                            echo "<button type='submit' class='btn btn-primary w-100'>Login</button>";
                            echo "</div>";
                            echo "</form>";
                        } else {
                            echo "<h4 class='text-center mb-3'>Logout Pasien</h4>";
                            echo "<form method='POST' action=''>";
                            echo "<input type='hidden' name='logout' value='true'>";
                            echo "<button type='submit' class='nav-link btn btn-primary w-100'>Logout</button>";
                            echo "</form>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
