<?php
if (!isset($_SESSION)) {
    session_start();
}

include_once("koneksi.php");
?>

<!doctype html>
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
                <?php
                if (isset($_SESSION['username'])) {
                    // Jika pengguna sudah login, tampilkan tombol "Logout"
                ?>

                    <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Data Master</a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="index.php?page=obat">Obat</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="Logout.php">Logout (<?php echo $_SESSION['username'] ?>)</a>
                        </li>
                    </ul>
                <?php
                } else {
                    // Jika pengguna belum login, tampilkan tombol "Login" dan "Register"
                ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=registerUser">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=loginUser">Login</a>
                        </li>
                    </ul>
                <?php
                }
                ?>
            </div>
        </div>
    </nav>


    <main role="main" class="container">
        <?php
        if (isset($_GET['page'])) {
            include($_GET['page'] . ".php");
        } else {
            echo "<br><h2>Selamat Datang di Sistem Informasi Poliklinik";

            if (isset($_SESSION['username'])) {
                //jika sudah login tampilkan username
                echo ", " . $_SESSION['username'] . "</h2><hr>";
            } else {
                echo "</h2><hr>Silakan Login untuk menggunakan sistem. Jika belum memiliki akun silakan Register terlebih dahulu.";
            }
        }
        $query = "SELECT id, nama_obat FROM obat ORDER BY id";
        $result = $mysqli->query($query);

        ?>
        <link rel="stylesheet" type="text/css" href="stylelist.css">
    </main>

    

    <div class="container">  
    <div class="row">
        <div class="col-lg-12 card-margin">
            <div class="card search-form">
                <div class="card-body p-0">
                    <form id="search-form">
                        <div class="row">
                            <div class="col-12">
                                <div class="row no-gutters">
                                    <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                                        <select class="form-control" id="exampleFormControlSelect1">
                                            <option>-- Pilih Obat --</option>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value='" . $row['id'] . "'>" . $row['nama_obat'] . "</option>";
                                                    }
                                                }
                                                ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-8 col-md-6 col-sm-12 p-0">
                                        <input type="text" placeholder="Search..." class="form-control" id="search" name="search">
                                    </div>
                                    <div class="col-lg-1 col-md-3 col-sm-12 p-0">
                                        <button type="submit" class="btn btn-base">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col-12">
                <div class="card card-margin">
                    <div class="card-body">
                        <div class="row search-body">
                            <div class="col-lg-12">
                                <div class="search-result">
                                    <div class="result-header">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="records">Showing: <b>1-20</b> of <b>200</b> result</div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="result-actions">
                                                    <div class="result-sorting">
                                                        <span>Sort By:</span>
                                                        <select class="form-control border-0" id="exampleOption">
                                                            <option value="1">Relevance</option>
                                                            <option value="2">Names (A-Z)</option>
                                                            <option value="3">Names (Z-A)</option>
                                                        </select>
                                                    </div>
                                                    <div class="result-views">
                                                        <button type="button" class="btn btn-soft-base btn-icon">
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                width="24"
                                                                height="24"
                                                                viewBox="0 0 24 24"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                stroke-width="2"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="feather feather-list"
                                                            >
                                                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                                                <line x1="3" y1="6" x2="3" y2="6"></line>
                                                                <line x1="3" y1="12" x2="3" y2="12"></line>
                                                                <line x1="3" y1="18" x2="3" y2="18"></line>
                                                            </svg>
                                                        </button>
                                                        <button type="button" class="btn btn-soft-base btn-icon">
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                width="24"
                                                                height="24"
                                                                viewBox="0 0 24 24"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                stroke-width="2"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="feather feather-grid"
                                                            >
                                                                <rect x="3" y="3" width="7" height="7"></rect>
                                                                <rect x="14" y="3" width="7" height="7"></rect>
                                                                <rect x="14" y="14" width="7" height="7"></rect>
                                                                <rect x="3" y="14" width="7" height="7"></rect>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="result-body">
                                        <div class="table-responsive">
                                            <table class="table widget-26">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="widget-26-job-emp-img">
                                                                <img src="https://bootdey.com/img/Content/avatar/avatar5.png" alt="Company" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-title">
                                                                <a href="#">Senior Software Engineer / Developer</a>
                                                                <p class="m-0"><a href="#" class="employer-name">Axiom Corp.</a> <span class="text-muted time">1 days ago</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-info">
                                                                <p class="type m-0">Full-Time</p>
                                                                <p class="text-muted m-0">in <span class="location">London, UK</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-salary">$ 50/hr</div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-category bg-soft-base">
                                                                <i class="indicator bg-base"></i>
                                                                <span>Software Development</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-starred">
                                                                <a href="#">
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24"
                                                                        height="24"
                                                                        viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="feather feather-star"
                                                                    >
                                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="widget-26-job-emp-img">
                                                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="Company" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-title">
                                                                <a href="#">Marketing &amp; Communication Supervisor</a>
                                                                <p class="m-0"><a href="#" class="employer-name">AxiomUI Llc.</a> <span class="text-muted time">2 days ago</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-info">
                                                                <p class="type m-0">Part-Time</p>
                                                                <p class="text-muted m-0">in <span class="location">New York, US</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-salary">$ 60/hr</div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-category bg-soft-warning">
                                                                <i class="indicator bg-warning"></i>
                                                                <span>Marketing</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-starred">
                                                                <a href="#">
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24"
                                                                        height="24"
                                                                        viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="feather feather-star"
                                                                    >
                                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="widget-26-job-emp-img">
                                                                <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="Company" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-title">
                                                                <a href="#">Senior Data Analyst / Scientist</a>
                                                                <p class="m-0"><a href="#" class="employer-name">AxiomUI Inc.</a> <span class="text-muted time">4 days ago</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-info">
                                                                <p class="type m-0">Part-Time</p>
                                                                <p class="text-muted m-0">in <span class="location">New York, US</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-salary">$ 60/hr</div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-category bg-soft-success">
                                                                <i class="indicator bg-success"></i>
                                                                <span>Artificial Intelligence</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-starred">
                                                                <a href="#">
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24"
                                                                        height="24"
                                                                        viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="feather feather-star"
                                                                    >
                                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="widget-26-job-emp-img">
                                                                <img src="https://bootdey.com/img/Content/avatar/avatar4.png" alt="Company" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-title">
                                                                <a href="#">UX Designer &amp; UI Developer</a>
                                                                <p class="m-0"><a href="#" class="employer-name">AxiomUI Inc.</a> <span class="text-muted time">5 days ago</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-info">
                                                                <p class="type m-0">Part-Time</p>
                                                                <p class="text-muted m-0">in <span class="location">Toronto, CAN</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-salary">$ 35/hr</div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-category bg-soft-danger">
                                                                <i class="indicator bg-danger"></i>
                                                                <span>Design</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-starred">
                                                                <a href="#">
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24"
                                                                        height="24"
                                                                        viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="feather feather-star"
                                                                    >
                                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="widget-26-job-emp-img">
                                                                <img src="https://bootdey.com/img/Content/avatar/avatar5.png" alt="Company" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-title">
                                                                <a href="#">Information Security Analyst / Expert</a>
                                                                <p class="m-0"><a href="#" class="employer-name">Axiom Corp.</a> <span class="text-muted time">6 days ago</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-info">
                                                                <p class="type m-0">Part-Time</p>
                                                                <p class="text-muted m-0">in <span class="location">Mumbai, IN</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-salary">$ 70/hr</div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-category bg-soft-info">
                                                                <i class="indicator bg-info"></i>
                                                                <span>Infra Supervision</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-starred">
                                                                <a href="#">
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24"
                                                                        height="24"
                                                                        viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="feather feather-star starred"
                                                                    >
                                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="widget-26-job-emp-img">
                                                                <img src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="Company" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-title">
                                                                <a href="#">Senior Software Engineer / Developer</a>
                                                                <p class="m-0"><a href="#" class="employer-name">Axiom Corp.</a> <span class="text-muted time">1 days ago</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-info">
                                                                <p class="type m-0">Full-Time</p>
                                                                <p class="text-muted m-0">in <span class="location">London, UK</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-salary">$ 50/hr</div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-category bg-soft-base">
                                                                <i class="indicator bg-base"></i>
                                                                <span>Software Development</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-starred">
                                                                <a href="#">
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24"
                                                                        height="24"
                                                                        viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="feather feather-star"
                                                                    >
                                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="widget-26-job-emp-img">
                                                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Company" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-title">
                                                                <a href="#">Marketing &amp; Communication Supervisor</a>
                                                                <p class="m-0"><a href="#" class="employer-name">AxiomUI Llc.</a> <span class="text-muted time">2 days ago</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-info">
                                                                <p class="type m-0">Part-Time</p>
                                                                <p class="text-muted m-0">in <span class="location">New York, US</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-salary">$ 60/hr</div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-category bg-soft-warning">
                                                                <i class="indicator bg-warning"></i>
                                                                <span>Marketing</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-starred">
                                                                <a href="#">
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24"
                                                                        height="24"
                                                                        viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="feather feather-star"
                                                                    >
                                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="widget-26-job-emp-img">
                                                                <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="Company" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-title">
                                                                <a href="#">Senior Data Analyst / Scientist</a>
                                                                <p class="m-0"><a href="#" class="employer-name">AxiomUI Inc.</a> <span class="text-muted time">4 days ago</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-info">
                                                                <p class="type m-0">Part-Time</p>
                                                                <p class="text-muted m-0">in <span class="location">New York, US</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-salary">$ 60/hr</div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-category bg-soft-success">
                                                                <i class="indicator bg-success"></i>
                                                                <span>Artificial Intelligence</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-starred">
                                                                <a href="#">
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24"
                                                                        height="24"
                                                                        viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="feather feather-star"
                                                                    >
                                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="widget-26-job-emp-img">
                                                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="Company" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-title">
                                                                <a href="#">UX Designer &amp; UI Developer</a>
                                                                <p class="m-0"><a href="#" class="employer-name">AxiomUI Inc.</a> <span class="text-muted time">5 days ago</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-info">
                                                                <p class="type m-0">Part-Time</p>
                                                                <p class="text-muted m-0">in <span class="location">Toronto, CAN</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-salary">$ 35/hr</div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-category bg-soft-danger">
                                                                <i class="indicator bg-danger"></i>
                                                                <span>Design</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-starred">
                                                                <a href="#">
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24"
                                                                        height="24"
                                                                        viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="feather feather-star"
                                                                    >
                                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="widget-26-job-emp-img">
                                                                <img src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="Company" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-title">
                                                                <a href="#">Information Security Analyst / Expert</a>
                                                                <p class="m-0"><a href="#" class="employer-name">Axiom Corp.</a> <span class="text-muted time">6 days ago</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-info">
                                                                <p class="type m-0">Part-Time</p>
                                                                <p class="text-muted m-0">in <span class="location">Mumbai, IN</span></p>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-salary">$ 70/hr</div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-category bg-soft-info">
                                                                <i class="indicator bg-info"></i>
                                                                <span>Infra Supervision</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="widget-26-job-starred">
                                                                <a href="#">
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24"
                                                                        height="24"
                                                                        viewBox="0 0 24 24"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="feather feather-star starred"
                                                                    >
                                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <nav class="d-flex justify-content-center">
                            <ul class="pagination pagination-base pagination-boxed pagination-square mb-0">
                                <li class="page-item">
                                    <a class="page-link no-border" href="#">
                                        <span aria-hidden="true">«</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link no-border" href="#">1</a></li>
                                <li class="page-item"><a class="page-link no-border" href="#">2</a></li>
                                <li class="page-item"><a class="page-link no-border" href="#">3</a></li>
                                <li class="page-item"><a class="page-link no-border" href="#">4</a></li>
                                <li class="page-item">
                                    <a class="page-link no-border" href="#">
                                        <span aria-hidden="true">»</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>    