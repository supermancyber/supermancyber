<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    // Sertakan koneksi ke database di sini (jika perlu)
    $host = 'localhost';
    $dbname = 'supermancyber';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ambil informasi pengguna
        $userId = $_SESSION['id'];
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insecure File Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="shadow w-450 p-3 text-center">
            <h3 class="display-4">Insecure File Upload</h3>
            <br>
            <form method="post" enctype="multipart/form-data" action="">
                <div class="mb-3">
                    <label for="uploaded_file" class="form-label">Select File:</label>
                    <input type="file" name="uploaded_file" id="uploaded_file" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
            <br>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Tentukan folder untuk menyimpan file
                $uploadDirectory = 'files/';

                // Dapatkan informasi file yang diunggah
                $fileName = $_FILES['uploaded_file']['name'];
                $fileTmpName = $_FILES['uploaded_file']['tmp_name'];

                // Pindahkan file ke folder yang ditentukan
                if (move_uploaded_file($fileTmpName, $uploadDirectory . $fileName)) {
                    echo "<p>File anda berhasil diunggah di /$uploadDirectory$fileName</p>";
                } else {
                    echo "<p>Maaf, terjadi kesalahan saat mengunggah file.</p>";
                }
            }
            ?>
            <a href="labs.php" class="btn btn-secondary">Back</a>
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
