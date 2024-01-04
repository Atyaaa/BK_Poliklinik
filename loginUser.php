<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa tabel 'user'
    $queryUser = "SELECT id, 'user' AS user_type, username, password FROM user WHERE username = '$username'";
    
    // Query untuk memeriksa tabel 'dokter'
    $queryDokter = "SELECT id, 'dokter' AS user_type, nama AS username, password FROM dokter WHERE nama = '$username'";

    // Gabungkan hasil dari kedua query menggunakan UNION
    $query = "$queryUser UNION $queryDokter";

    $result = $mysqli->query($query);

    if (!$result) {
        die("Query error: " . $query . "<br>" . $mysqli->error);
    }

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['username'] = $row['username'];

            // Redirect sesuai dengan jenis pengguna
            if ($row['user_type'] == 'user') {
                header("Location: indexadmin.php");
                exit();
            } elseif ($row['user_type'] == 'dokter') {
                header("Location: indexdokter.php");
                $_SESSION['id_dokter'] = $row['id'];
                exit();
            } else {
                $error = "Jenis pengguna tidak valid";
            }
        } else {
            $error = "Password salah";
        }
    } else {
        $error = "User tidak ditemukan";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Login</title>
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
                                            <h4 class="mb-4 pb-3">Log In</h4>
                                            <form method="POST" action="index.php?page=loginUser">
                                                <div class="form-group">
                                                    <input type="text" name="username" class="form-control" required
                                                        placeholder="Masukkan nama anda">
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" name="password" class="form-control"
                                                        required placeholder="Masukkan password anda">
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-block mt-4">Login</button>
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
