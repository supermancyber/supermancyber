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
    
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CSRF</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            .box {
                margin: 20px auto; /* Tengahkan box dan tambahkan batas atas dan bawah */
                max-width: 1000px; /* Lebar maksimum box */
                overflow-y: auto; /* Biarkan konten di dalam box discroll ketika melebihi batas */
                max-height: calc(100vh - 40px); /* Atur tinggi maksimum box agar sesuai dengan ketinggian layar - batas atas dan bawah */
            }
            .paragraph {
                text-align: justify; /* Justify paragraf */
            }
            .hide {
                display: none; /* Sembunyikan konten yang akan diperluas */
            }
            .display-4 {
                font-size: 48px;
            }
        </style>
    </head>
    <body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="shadow p-3 text-center box">
            <h4 class="display-4">Help</h4><br><br>
            <p class="paragraph">Selamat datang di aplikasi web supermancyber!</p>
            <p class="paragraph">Aplikasi ini merupakan platform yang dapat digunakan untuk belajar penetration testing.</p>
            <p class="paragraph">Kalian bisa mulai dengan memahami konsep dasar <a href="theory.php">disini</a>. Mulai dari setup tools hingga teori-teori kerentanan aplikasi web.</p>
            <p class="paragraph">Untuk belajar praktek, kalian bisa klik <a href="home.php">disini</a> untuk mengerjakan lab yang sudah disediakan di aplikasi ini.</p>
            <p class="paragraph">Selamat belajar!</p><br>
            <div class="d-flex flex-column align-items-center"> <!-- Mengelompokkan tombol-tombol 'Back' -->
                <a href="labs.php" class="btn btn-secondary btn-block mb-3">Back to Labs</a>
                <a href="theory.php" class="btn btn-secondary">Back to Theory</a>
            </div>
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
