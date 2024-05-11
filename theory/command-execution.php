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
        <title>Command Execution</title>
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
        </style>
    </head>
    <body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="shadow p-3 text-center box"> <!-- Tambahkan kelas box -->
            <h4 class="display-4">Commmand Execution</h4><br><br>
            <p class="paragraph">Command Execution merupakan jenis serangan keamanan di mana penyerang mencoba menjalankan perintah atau skrip di server yang menjalankan aplikasi web. Serangan ini biasanya terjadi ketika aplikasi web menerima input dari pengguna yang kemudian dieksekusi secara langsung oleh server, tanpa dilakukan validasi atau penyaringan yang memadai.</p>
            <p class="paragraph">Serangan Command Execution agak jarang muncul di aplikasi web karena umumnya tidak ada alasan bagi sebuah aplikasi web untuk mengeksekusi perintah sistem pada server. Namun, dalam beberapa kasus, serangan ini bisa dimungkinkan jika aplikasi web memiliki kerentanan keamanan yang memungkinkan pengguna untuk menyisipkan input yang dieksekusi oleh server.</p>
            <div class="box d-flex flex-column">
                <img src="../photos/command-execution.jpeg" width=600px>
            </div>
            <p class="paragraph">Dampak dari serangan Command Execution bisa sangat berbahaya, termasuk pengambilalihan kontrol atas server, akses ke data sensitif, dan bahkan penghapusan atau modifikasi konfigurasi sistem.</p>
            <p class="paragraph">Pada laboratorium yang disediakan, kerentanan Command Execution sengaja dibuat agar pengguna dapat memahami cara kerja serangan ini dan belajar cara melindungi aplikasi web dari serangan semacam itu. Ini menunjukkan pentingnya untuk selalu melakukan validasi dan penyaringan input, serta mengimplementasikan prinsip keamanan terbaik dalam pengembangan aplikasi web.</p>
            <!-- Tombol "see more" expand untuk menampilkan kunci jawaban -->
            <button class="btn btn-primary mt-3" onclick="toggleAnswer()">Kunci Jawaban Lab</button>
            <div id="answer" class="hide mt-3">
                <p class="paragraph">Kunci Jawaban: </p>
                <p class="paragraph">1. Klik "Command Execution" pada labs.</p>
                <p class="paragraph">2. Ketik perintah OS apapun (misal: "ls") dan klik "submit" untuk mengeksekusi perintah tersebut.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/command-execution.png" width=750px>
                </div>
                <p class="paragraph">Perhatikan tipe operating system dari mesin lokal anda. Jika menggunakan windows, maka gunakanlah perintah OS windows.</p>
                <p class="paragraph">Kerentanan ini memang jarang sekali ada karena tidak ada alasan bagi developer untuk mengeksekusi perintah OS dalam aplikasi web.</p>
                <p class="paragraph">Berikut ini merupakan video POC untuk kerentanan command execution:</p>
                <div class="box d-flex flex-column">
                    <video width="900px" controls>
                        <source src="../videos/command-execution.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <br><br><a href="../theory.php" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <!-- Skrip JavaScript untuk menampilkan/menyembunyikan kunci jawaban -->
    <script>
        function toggleAnswer() {
            var answer = document.getElementById("answer");
            if (answer.classList.contains("hide")) {
                answer.classList.remove("hide");
            } else {
                answer.classList.add("hide");
            }
        }
    </script>
    </body>
    </html>
    <?php
} else {
    header("Location: index.php");
    exit;
}
?>
