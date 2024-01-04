<?php
    include('koneksi.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['username'])) {
        // Jika pengguna belum login, redirect ke halaman login
        header("Location: login.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpanPeriksa'])) {
        $hasil_pemeriksaan = $_POST['hasil_pemeriksaan'];
        $id_daftar_poli = $_POST['id_daftar_poli'];
        $tgl_periksa = $_POST['tgl_periksa'];
        $total_biaya_obat = $_POST['total_biaya'];
        $total_biaya_periksa = $_POST['total_biaya_periksa'];
        $obat_terpilih = isset($_POST['obat']) ? $_POST['obat'] : [];

        // Insert data ke tabel periksa
        $queryInsertPeriksa = "INSERT INTO periksa (id_daftar_poli, tgl_periksa, catatan, biaya_periksa) 
                                VALUES ('$id_daftar_poli', '$tgl_periksa', '$hasil_pemeriksaan', '$total_biaya_obat')";
        $resultInsertPeriksa = $mysqli->query($queryInsertPeriksa);

        if ($resultInsertPeriksa) {
            // Ambil ID periksa yang baru saja diinsert
            $id_periksa = $mysqli->insert_id;

            // Loop untuk setiap obat yang dipilih
            foreach ($obat_terpilih as $id_obat) {
                // Insert ke tabel detail_periksa
                $queryInsertDetailPeriksa = "INSERT INTO detail_periksa (id_periksa, id_obat) 
                                            VALUES ('$id_periksa', '$id_obat')";
                $resultInsertDetailPeriksa = $mysqli->query($queryInsertDetailPeriksa);

                // Check keberhasilan penyisipan detail_periksa
                if (!$resultInsertDetailPeriksa) {
                    echo "Gagal menyimpan detail periksa: " . $mysqli->error;
                    exit;
                }
            }

            // Setelah semua obat berhasil disimpan, mungkin Anda ingin melakukan redirect atau menampilkan pesan sukses
            echo "Pemeriksaan dan detail obat berhasil disimpan.";
        } else {
            echo "Gagal menyimpan ke tabel periksa: " . $mysqli->error;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Halaman Periksa</title>
        <!-- Tambahkan link stylesheet Bootstrap jika belum ada -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
            integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
            crossorigin="anonymous">
    </head>

    <body>
        <main role="main" class="container">
        <link rel="stylesheet" type="text/css" href="stylelist.css">
            <h2>Selamat Datang Dok, Ini Adalah Halaman Periksa</h2>
            <p>Hallooo, <?= $_SESSION['username']; ?>!</p>

            <?php
            // Query untuk mengambil data daftar_poli dan mengurutkannya berdasarkan no_antrian
            $queryDaftarAntrian = "SELECT daftar_poli.id AS id_daftar_poli, no_antrian, pasien.nama AS nama_pasien, jadwal_periksa.hari, jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai, poli.nama_poli, dokter.nama AS nama_dokter, daftar_poli.keluhan
                                FROM daftar_poli
                                INNER JOIN pasien ON daftar_poli.id_pasien = pasien.id
                                INNER JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
                                INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id
                                INNER JOIN poli ON dokter.id_poli = poli.id
                                ORDER BY no_antrian";

            $resultDaftarAntrian = $mysqli->query($queryDaftarAntrian);

            if ($resultDaftarAntrian) {
            ?>
            <div class="container">
                <h3 class="mt-4">Daftar Antrian Poliklinik:</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">No Antrian</th>
                                <th scope="col">Nama Pasien</th>
                                <th scope="col">Nomor Id</th>
                                <th scope="col">Jadwal</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $resultDaftarAntrian->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['no_antrian'] . "</td>";
                                echo "<td>" . $row['nama_pasien'] . "</td>";
                                echo "<td>" . $row['id_daftar_poli'] . "</td>";
                                echo "<td>" . $row['hari'] . ", " . $row['jam_mulai'] . " - " . $row['jam_selesai'] . ", " .
                                    $row['nama_poli'] . " - Dr. " . $row['nama_dokter'] . "</td>";
                                echo "<td><button class='btn btn-primary' data-toggle='modal' data-target='#periksaModal{$row['no_antrian']}'>Periksa</button></td>";
                                echo "</tr>";

                                // Modal
                                echo "<div class='modal fade' id='periksaModal{$row['no_antrian']}' tabindex='-1' role='dialog' aria-labelledby='periksaModalLabel' aria-hidden='true'>";
                                echo "<div class='modal-dialog' role='document'>";
                                echo "<div class='modal-content'>";
                                echo "<div class='modal-header'>";
                                echo "<h5 class='modal-title' id='periksaModalLabel'>Periksa Pasien</h5>";
                                echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                                echo "<span aria-hidden='true'>&times;</span>";
                                echo "</button>";
                                echo "</div>";
                                echo "<div class='modal-body'>";
                                // Tampilkan data dan form periksa di sini
                                echo "<p>Nama Pasien: {$row['nama_pasien']}</p>";
                                echo "<p>Keluhan: {$row['keluhan']}</p>";
                                echo "<p>id_daftar_poli: {$row['id_daftar_poli']}</p>";
                                // Tambahkan form untuk hasil pemeriksaan, obat, dan total biaya
                                echo "<form action='indexdokter.php?page=periksa' method='POST'>";
                                echo "<input type='hidden' name='no_antrian' value='{$row['no_antrian']}'>";
                                echo "<input type='hidden' name='tgl_periksa' value='" . date('Y-m-d') . "'>";
                                echo "<input type='hidden' name='id_daftar_poli' value='{$row['id_daftar_poli']}'>";
                                echo "<div class='form-group'>";
                                echo "<label for='hasil_pemeriksaan'>Hasil Pemeriksaan:</label>";
                                echo "<textarea class='form-control' name='hasil_pemeriksaan' rows='3' required></textarea>";
                                echo "</div>";

                                // Bagian 1: List obat yang ada
                                $queryObat = "SELECT * FROM obat";
                                $resultObat = $mysqli->query($queryObat);

                                echo "<div class='form-group'>";
                                echo "<label for='list_obat'>List Obat:</label>";
                                echo "<select class='form-control' name='list_obat' size='5'>";

                                while ($rowObat = $resultObat->fetch_assoc()) {
                                    echo "<option value='{$rowObat['id']}' data-harga='{$rowObat['harga']}'>{$rowObat['nama_obat']}</option>";
                                }

                                echo "</select>";
                                echo "</div>";

                                // Bagian 2: Tombol pilih dibawah list obat
                                echo "<button type='button' class='btn btn-success' id='pilihObatBtn'>Pilih Obat</button>";

                                // Bagian 3: Obat yang dipilih
                                echo "<div class='form-group mt-3'>";
                                echo "<label for='obat'>Obat yang Dipilih:</label>";
                                echo "<select multiple class='form-control' name='obat[]' id='obatPilihan'>";
                                echo "</select>";
                                echo "</div>";

                                // Bagian 4: Tombol hapus dibawah obat dipilih
                                echo "<button type='button' class='btn btn-danger' id='hapusObatBtn'>Hapus Obat</button>";

                                // Tambahkan input untuk total biaya
                                echo "<div class='form-group'>";
                                echo "<label for='total_biaya'>Total Biaya:</label>";
                                echo "<label for='total_biaya'>*Total harga sudah termasuk biaya periksa Rp.150.000</label>";
                                echo "<input type='text' class='form-control' name='total_biaya' readonly>";
                                echo "</div>";

                                // Tambahkan input tersembunyi untuk total biaya periksa
                                echo "<input type='hidden' name='total_biaya_periksa' id='total_biaya_periksa' value=''>";
                                echo "<button type='submit' class='btn btn-primary' name='simpanPeriksa'>Simpan</button>";
                                echo "</form>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php

            } else {
                echo "Terjadi kesalahan saat mengambil data daftar antrian poliklinik: " . $mysqli->error;
            }
            ?>

        </main>

        <!-- Tambahkan script Bootstrap dan JavaScript jika belum ada -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <script>
            // Tambahkan script untuk menghitung total biaya
            $(document).ready(function() {
                $('select[name="obat[]"]').change(function() {
                    var totalBiaya = 150000;
                    $('select[name="obat[]"] option:selected').each(function() {
                        totalBiaya += parseFloat($(this).data('harga'));
                    });
                    $('input[name="total_biaya"]').val(totalBiaya);

                    // Perbarui total_biaya_periksa
                    var totalBiayaPeriksa = totalBiaya + parseFloat($('#total_biaya_periksa').val());
                    $('#total_biaya_periksa').val(totalBiayaPeriksa);
                    $total_biaya_periksa += $total_biaya_obat;
                });

                // Bagian 2: Tombol pilih dibawah list obat
                $('#pilihObatBtn').click(function() {
                    var selectedObat = $('select[name="list_obat"] option:selected');

                    // Pindahkan obat yang dipilih ke bagian 3
                    selectedObat.clone().appendTo('#obatPilihan');
                });

                // Bagian 3: Menghitung total biaya obat yang dipilih
                $('#obatPilihan').change(function() {
                    var totalBiaya = 150000;
                    $('#obatPilihan option').each(function() {
                        totalBiaya += parseFloat($(this).data('harga'));
                    });
                    $('input[name="total_biaya"]').val(totalBiaya);
                });
                // Bagian 4: Tombol hapus di bawah "obat yang dipilih"
                $('#hapusObatBtn').click(function() {
                    var selectedObat = $('#obatPilihan option:selected');

                    // Hapus obat yang dipilih
                    selectedObat.remove().empty();

                    // Hitung kembali total biaya setelah menghapus
                    var totalBiaya = 150000;
                    $('#obatPilihan option').each(function() {
                        totalBiaya += parseFloat($(this).data('harga'));
                    });
                    $('input[name="total_biaya"]').val(totalBiaya);
                });

            });
        </script>
    </body>

</html>
