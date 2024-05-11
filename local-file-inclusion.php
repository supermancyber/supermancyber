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
    <title>Local File Inclusion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        #file_content {
            max-height: 300px; /* Tentukan tinggi maksimum konten yang dapat ditampilkan */
            overflow-y: auto; /* Tambahkan scroll jika konten melebihi tinggi maksimum */
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="shadow w-450 p-3 text-center" style="max-width: 1000px">
            <h3 class="display-4">Local File Inclusion</h3>
            <br>
            <form method="get" action="">
                <label for="file_path">File Path:</label>
                <input type="text" name="file_path" id="file_path" required>
                <button type="submit">Read File</button>
            </form>
            <br>
            <div id="file_content">
                <?php
                // Tangkap input dari parameter file_path jika ada
                $filePath = isset($_GET['file_path']) ? $_GET['file_path'] : '';

                // Periksa apakah parameter file_path tidak kosong
                if (!empty($filePath)) {
                    // Baca isi file dari server
                    $fileContent = file_get_contents($filePath);
                    
                    // Tampilkan isi file jika berhasil dibaca
                    if ($fileContent !== false) {
                        echo "<pre>" . htmlspecialchars($fileContent) . "</pre>";
                    } else {
                        echo "<p>File not found or inaccessible.</p>";
                    }
                }
                ?>
            </div>
            <br><br>
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
