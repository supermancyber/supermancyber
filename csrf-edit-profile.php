<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    // Sertakan kode koneksi ke database di sini

    $host = 'localhost';
    $dbname = 'supermancyber';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ambil informasi pengguna dari database
        $userId = $_SESSION['id'];
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    
    // Tampilkan formulir edit profil
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Profil</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="shadow w-450 p-3 text-center">
                <h3 style="font-size: 40px;" class="display-4">CSRF - Edit Profile</h3><br>
                <!-- Tampilkan formulir dengan data pengguna -->
                <form action="php/csrf-update-profile.php" method="post">
                    <!-- Input tersembunyi untuk menyimpan ID pengguna -->
                    <input type="hidden" name="userId" value="<?= htmlspecialchars($userData['id'], ENT_QUOTES, 'UTF-8') ?>">

                    <!-- Tampilkan data pengguna di dalam bidang formulir, misalnya $userData['fname'], $userData['email'], dll. -->
                    <label for="fname">Nama Depan:</label>
                    <input type="text" name="fname" value="<?= htmlspecialchars($userData['fname'], ENT_QUOTES, 'UTF-8') ?>" required>

                    <!-- Tambahkan bidang formulir lain untuk atribut pengguna lainnya -->

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                <br><a href="labs.php" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    header("Location: index.php");
    exit;
}
?>
