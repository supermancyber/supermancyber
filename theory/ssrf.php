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
        <title>SSRF</title>
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
            <h4 class="display-4">Server Side Request Forgery (SSRF)</h4><br><br>
            <p class="paragraph">Server Side Request Forgery (SSRF) adalah kerentanan keamanan yang memungkinkan penyerang untuk memanipulasi server untuk melakukan permintaan ke sumber daya internal atau eksternal yang mungkin tidak seharusnya dapat diakses olehnya. Kerentanan ini sering kali memanfaatkan kurangnya validasi input pengguna pada aplikasi yang memungkinkan penyerang untuk mengontrol alamat URL yang digunakan oleh server untuk melakukan permintaan HTTP.</p>
            <div class="box d-flex flex-column">
                <img src="../photos/ssrf.png" width=750px>
            </div>
            <p class="paragraph">Dampak dari SSRF dapat sangat berbahaya, termasuk:</p>
            <p class="paragraph">1. Akses Tidak Sah ke Sumber Daya Internal: Penyerang dapat memanipulasi server untuk mengakses sumber daya internal seperti file sistem, basis data internal, atau layanan administratif yang tidak seharusnya dapat diakses olehnya.</p>
            <p class="paragraph">2. Penyusupan ke Jaringan Internal: Penyerang dapat menggunakan SSRF untuk mengeksploitasi server dan menyerang sumber daya internal jaringan, seperti server database atau sistem manajemen jaringan.</p>
            <p class="paragraph">Untuk mencegah SSRF, penting untuk melakukan validasi dan sanitasi input pengguna dengan cermat, membatasi akses server ke sumber daya internal yang diperlukan, serta memperbarui dan memantau konfigurasi server secara teratur untuk mengidentifikasi dan memperbaiki potensi kerentanan. Selain itu, menggunakan daftar putih alamat URL yang diizinkan dan memperbarui perangkat lunak dengan patch keamanan terbaru juga dapat membantu melindungi aplikasi dari serangan SSRF.</p>
            <!-- Tombol "see more" expand untuk menampilkan kunci jawaban -->
            <button class="btn btn-primary mt-3" onclick="toggleAnswer()">Kunci Jawaban</button>
            <a href="../ssrf.php" class="btn btn-primary mt-3 ms-2">Latihan</a>
            <div id="answer" class="hide mt-3">
                <p class="paragraph">Kunci Jawaban:</p>
                <p class="paragraph">1. Klik "Server Side Request Forgery" pada labs.</p>
                <p class="paragraph">2. Bisa dilihat bahwa terdapat fitur fetch content, namun request ini bukan berasal dari user, melainkan dari server.</p>
                <p class="paragraph">3. Buka burp suite professional (karena fitur collaborator hanya tersedia untuk burp suite professional).</p>
                <p class="paragraph">4. Pada burp suite, pilih "collaborator" kemudian klik "get started".</p>
                <p class="paragraph">5. Kemudian, klik "copy to clipboard" lalu paste ke aplikasi. Jangan lupa gunakan https.</p>
                <p class="paragraph">6. Klik "fetch content", kemudian kembali ke burp suite.</p>
                <p class="paragraph">7. Klik "poll now" dan bisa dilihat bahwa terdapat http request yang berasal dari web server.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/ssrf-1.png" width=750px>
                </div>
                <p class="paragraph">Dalam skenario ini, IP yang terdapat pada burp suite adalah IP publik dari komputer kita sendiri karena web server berada di localhost.</p>
                <p class="paragraph">Mitigasi dari kerentanan tersebut adalah dengan membatasi domain yang dapat diakses agar penyerang tidak dapat mengeksploitasi kerentanan ini untuk scanning internal port, dan lain-lain.</p>
                <p class="paragraph">Berikut ini merupakan video POC untuk kerentanan SSRF:</p>
                <div class="box d-flex flex-column">
                    <video width="900px" controls>
                        <source src="../videos/ssrf.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <br><br><a href="../labs.php" class="btn btn-secondary">Back</a>
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
