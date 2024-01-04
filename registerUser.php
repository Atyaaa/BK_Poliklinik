<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];

    // Periksa apakah pasien sudah terdaftar berdasarkan no KTP
    $queryCheckPasien = "SELECT * FROM pasien WHERE no_ktp = '$no_ktp'";
    $resultCheckPasien = $mysqli->query($queryCheckPasien);

    if (!$resultCheckPasien) {
        die("Query error: " . $mysqli->error);
    }

    if ($resultCheckPasien->num_rows > 0) {
        // Pasien sudah terdaftar, tampilkan pesan error
        $error = "Pasien dengan nomor KTP tersebut sudah terdaftar.";
    } else {
        // Pasien belum terdaftar, lakukan pendaftaran
        // Dapatkan tahun dan bulan saat ini
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');

        // Dapatkan nomor urut pasien untuk bulan ini
        $queryGetNomorUrut = "SELECT COUNT(*) as jumlah_pasien FROM pasien WHERE MONTH(NOW()) = '$bulan_sekarang'";
        $resultGetNomorUrut = $mysqli->query($queryGetNomorUrut);


        if ($resultGetNomorUrut) {
            $rowNomorUrut = $resultGetNomorUrut->fetch_assoc();
            $nomor_urut = $rowNomorUrut['jumlah_pasien'] + 1;
        
            // Format nomor rekam medis: tahun bulan - nomor urut
            $nomor_rm = $tahun_sekarang . str_pad($bulan_sekarang, 2, '0', STR_PAD_LEFT) . '-' . $nomor_urut;
        
            // Simpan data pasien ke dalam database
            $queryInsertPasien = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$nomor_rm')";
            $resultInsertPasien = $mysqli->query($queryInsertPasien);
        
            if ($resultInsertPasien) {
                $pesan = "Pendaftaran berhasil. Nomor Rekam Medis: $nomor_rm";
            } else {
                $error = "Terjadi kesalahan saat mendaftarkan pasien: " . $mysqli->error;
            }
        } else {
            $error = "Terjadi kesalahan saat mengambil nomor urut.";
        }

        $pesan = "Pendaftaran berhasil. Nomor Rekam Medis: $nomor_rm";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Pendaftaran Pasien</title>
</head>

<body>
    <div class="section">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 text-center align-self-center py-5">
                    <div class="section pb-5 pt-5 pt-sm-2 text-center">
                        <div class="card-3d-wrap mx-auto">
                            <div class="card-3d-wrapper">
                                <div class="card-front">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Pendaftaran Pasien</h4>

                                            <?php
                                            // Tampilkan pesan sukses atau error
                                            if (isset($pesan)) {
                                                echo "<div class='alert alert-success'>$pesan</div>";
                                            }

                                            if (isset($error)) {
                                                echo "<div class='alert alert-danger'>$error</div>";
                                            }
                                            ?>

                                            <form method="POST" action="index.php?page=registerUser">
                                                <div class="form-group">
                                                    <input type="text" name="nama" class="form-control" required
                                                        placeholder="Masukkan nama anda">
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <input type="text" name="alamat" class="form-control" required
                                                        placeholder="Masukkan alamat anda">
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <input type="text" name="no_ktp" class="form-control" required
                                                        placeholder="Masukkan nomer ktp anda">
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <input type="text" name="no_hp" class="form-control" required
                                                        placeholder="Masukkan nomer hp anda">
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-block mt-4">Daftar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
