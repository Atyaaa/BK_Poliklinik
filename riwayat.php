<?php
include('koneksi.php');

// Query untuk mengambil data pasien dari tabel periksa
$queryPasien = "SELECT detail_periksa.id AS nomer, id_periksa, id_obat, periksa.id AS id_periksa, obat.nama_obat AS namaobat, dokter.nama AS namadokter, periksa.catatan, periksa.tgl_periksa, periksa.biaya_periksa AS totalbiaya, daftar_poli.id_pasien, pasien.nama AS nama_pasien, pasien.alamat AS alamat_pasien, pasien.no_ktp AS ktp_pasien, pasien.no_hp AS hp_pasien, pasien.no_rm AS rm_pasien
                FROM detail_periksa
                INNER JOIN obat ON obat.id = id_obat
                INNER JOIN periksa ON periksa.id = id_periksa
                INNER JOIN daftar_poli ON periksa.id_daftar_poli = daftar_poli.id
                INNER JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
                INNER JOIN dokter ON dokter.id = jadwal_periksa.id_dokter
                INNER JOIN pasien ON daftar_poli.id_pasien = pasien.id";

$resultPasien = $mysqli->query($queryPasien);

if (!$resultPasien) {
    // Tambahkan penanganan kesalahan
    die("Error: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pasien</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
<main role="main" class="container">
        <h2>Ini Halaman Riwayat Pasien Dok</h2>

        <?php
        if ($resultPasien) {
        ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nama Pasien</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">No.KTP</th>
                            <th scope="col">No.Telepon</th>
                            <th scope="col">Nomor RM</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($rowPasien = $resultPasien->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $rowPasien['nomer'] . "</td>";
                            echo "<td>" . $rowPasien['nama_pasien'] . "</td>";
                            echo "<td>" . $rowPasien['alamat_pasien'] . "</td>";
                            echo "<td>" . $rowPasien['ktp_pasien'] . "</td>";
                            echo "<td>" . $rowPasien['hp_pasien'] . "</td>";
                            echo "<td>" . $rowPasien['rm_pasien'] . "</td>";
                            $rowPasien['tgl_periksa'];
                            $rowPasien['namadokter'];
                            $rowPasien['catatan'];
                            $rowPasien['namaobat'];
                            $rowPasien['totalbiaya'];
                            echo "<td><button class='btn btn-info' data-toggle='modal' data-target='#detailModal{$rowPasien['ktp_pasien']}'>Lihat Detail</button></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php
            // Reset result set pointer
            mysqli_data_seek($resultPasien, 0);

            while ($rowPasien = $resultPasien->fetch_assoc()) {
            ?>
                <!-- Modal for each row -->
                <div class='modal fade' id='detailModal<?php echo $rowPasien['ktp_pasien']; ?>' tabindex='-1' role='dialog' aria-labelledby='detailModalLabel' aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='detailModalLabel'>Detail Pasien</h5>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                            <div class='modal-body'>
                                <!-- Table to display details -->
                                <table class='table'>
                                    <tr><th>Nama Pasien</th><td><?php echo $rowPasien['nama_pasien']; ?></td></tr>
                                    <tr><th>Tanggal Periksa</th><td><?php echo $rowPasien['tgl_periksa']; ?></td></tr>
                                    <tr><th>Nama Dokter</th><td><?php echo $rowPasien['namadokter']; ?></td></tr>
                                    <tr><th>Keluhan</th><td><?php echo $rowPasien['catatan']; ?></td></tr>
                                    <tr><th>Obat</th><td><?php echo $rowPasien['namaobat']; ?></td></tr>
                                    <tr><th>Biaya Periksa</th><td><?php echo $rowPasien['totalbiaya']; ?></td></tr>
                                </table>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        <?php
        } else {
            echo "Terjadi kesalahan saat mengambil data pasien: " . $mysqli->error;
        }
        ?>

    </main>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>
