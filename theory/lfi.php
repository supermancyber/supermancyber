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
        <title>LFI</title>
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
        <div class="shadow p-3 text-center box">
            <h4 class="display-4">Local File Inclusion (LFI)</h4><br><br>
            <p class="paragraph">Local File Inclusion (LFI) adalah jenis serangan keamanan yang memungkinkan penyerang untuk menyisipkan dan menjalankan file lokal di server melalui kerentanan pada aplikasi web. Kerentanan ini terjadi ketika aplikasi web memungkinkan pengguna untuk memasukkan jalur file sebagai input tanpa melakukan validasi atau sanitasi yang memadai.</p>
            <p class="paragraph">Meskipun kerentanan ini memiliki dampak signifikan, serangan LFI tidak terlalu umum pada aplikasi web modern yang dibangun dengan praktik keamanan terbaru. Hal ini disebabkan oleh meningkatnya kesadaran akan keamanan perangkat lunak dan peningkatan penggunaan framework yang menyediakan lapisan keamanan tambahan.</p>
            <p class="paragraph">Dengan menggunakan serangan LFI, penyerang dapat mencoba mengakses dan mengeksekusi file lokal di server, termasuk file sistem, konfigurasi, atau bahkan file sensitif seperti file yang berisi informasi kredensial atau data penting lainnya.</p>
            <p class="paragraph">Contoh umum serangan LFI adalah ketika sebuah aplikasi web memiliki parameter URL yang digunakan untuk memuat file tertentu, misalnya untuk menampilkan konten dinamis. Jika parameter tersebut tidak divalidasi dengan benar, penyerang dapat menyisipkan jalur file lokal yang kemudian akan dieksekusi oleh server.</p>
            <div class="box d-flex flex-column">
                    <img src="../photos/lfi.png" width=600px>
                </div>
            <p class="paragraph">Dampak dari serangan LFI dapat sangat serius, termasuk pengambilalihan kontrol atas server, pencurian data sensitif, dan bahkan eskalasi hak akses ke server yang terinfeksi.</p>
            <p class="paragraph">Pada laboratorium yang disediakan, kerentanan LFI disediakan untuk memungkinkan pengguna memahami cara serangan ini dilakukan dan bagaimana untuk melindungi aplikasi web dari kerentanan semacam itu. Ini menunjukkan pentingnya untuk selalu melakukan validasi input dan membatasi akses file lokal agar tidak terjadi serangan LFI pada aplikasi web.</p>
            <!-- Tombol "see more" expand untuk menampilkan kunci jawaban -->
            <button class="btn btn-primary mt-3" onclick="toggleAnswer()">Kunci Jawaban</button>
            <a href="../local-file-inclusion.php" class="btn btn-primary mt-3 ms-2">Latihan</a>
            <div id="answer" class="hide mt-3">
                <p class="paragraph">Kunci Jawaban:</p>
                <p class="paragraph">1. Klik "Local File Inclusion" pada labs.</p>
                <p class="paragraph">2. Ketikkan file apapun yang terdapat pada aplikasi ini, misal "help.php" kemudian klik "read file" untuk melihat isi konten dari file tersebut.</p>
                <p class="paragraph">3. Perhatikan bahwa kerentanan ini terdapat pada parameter "file_path".</p>
                <p class="paragraph">4. Kerentanan ini berbahaya karena penyerang dapat membaca file sensitif yang berada pada server, contohnya "/etc/passwd".</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/lfi-1.png" width=750px>
                </div>
                <p class="paragraph">Kerentanan LFI ini tidak terlalu umum, namun perlu diperhatikan bagi para developer yang membuat aplikasi web.</p>
                <p class="paragraph">Berikut ini merupakan video POC untuk kerentanan LFI:</p>
                <div class="box d-flex flex-column">
                    <video width="900px" controls>
                        <source src="../videos/lfi.mp4" type="video/mp4">
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
