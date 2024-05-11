<?php
// Mulai sesi jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    // Jika sudah login, arahkan ke halaman labs.php
    header("Location: labs.php");
    exit;
}

// Jika terdapat pesan kesalahan yang dikirim melalui GET, tangani pesan tersebut
$error = isset($_GET['error']) ? $_GET['error'] : "";

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            background-image: url('path_to_your_image.jpg'); /* Ganti 'path_to_your_image.jpg' dengan path ke gambar background Anda */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        
        <form class="shadow w-450 p-3" 
              action="php/login.php" 
              method="post">

            <h4 class="display-4  fs-1">LOGIN</h4><br>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">User name</label>
                <input type="text" 
                       class="form-control"
                       name="uname"
                       value="<?php echo isset($_GET['uname']) ? $_GET['uname'] : '' ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" 
                       class="form-control"
                       name="pass">
            </div>
            
            <button type="submit" class="btn btn-primary" style="margin-right: 10px">Login</button>
            <a href="signup.php" class="link-secondary">Sign Up</a>
        </form>
    </div>
</body>
</html>
