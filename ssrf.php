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
    <title>SSRF Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        #content {
            max-width: 600px; /* Tentukan lebar maksimum konten yang dapat ditampilkan */
            max-height: 300px; /* Tentukan tinggi maksimum konten yang dapat ditampilkan */
            overflow-y: auto; /* Tambahkan scroll jika konten melebihi tinggi maksimum */
            margin: 0 auto; /* Pusatkan konten di tengah halaman */
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="shadow p-3 text-center">
            <h3 class="display-4">SSRF Example</h3>
            <br>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="url_input" class="form-label">Enter URL:</label>
                    <input type="text" name="url_input" id="url_input" required>
                </div>
                <button type="submit" class="btn btn-primary">Fetch Content</button>
            </form>
            <br>
            <div id="content">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Dapatkan URL yang dimasukkan oleh pengguna
                    $url = $_POST['url_input'];

                    // Ambil konten dari URL
                    $content = file_get_contents($url);

                    // Tampilkan konten kepada pengguna
                    echo "<pre>" . htmlspecialchars($content) . "</pre>";
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
