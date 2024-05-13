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
        <title>IDOR</title>
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
        <div class="shadow p-3 text-center box" style="text-align: left"> <!-- Tambahkan kelas box -->
            <h4 class="display-4">Insecure Direct Object Reference (IDOR)</h4><br><br>
            <p class="paragraph">Insecure Direct Object Reference (IDOR) adalah sebuah kerentanan keamanan pada aplikasi web yang terjadi ketika suatu objek yang sensitif, seperti data pengguna atau informasi yang seharusnya terbatas aksesnya, dapat diakses secara langsung oleh pengguna tanpa adanya kontrol atau validasi yang memadai.</p>
            <p class="paragraph">Pada umumnya, aplikasi web memiliki kontrol akses yang memastikan bahwa pengguna hanya dapat mengakses objek atau data yang seharusnya mereka akses, berdasarkan hak akses atau peran pengguna tersebut. Namun, dalam kasus IDOR, kontrol akses tersebut tidak diterapkan dengan benar, sehingga pengguna dapat mengakses objek atau data yang seharusnya tidak mereka akses.</p>
            <p class="paragraph">Contoh sederhana dari IDOR adalah ketika suatu aplikasi web memiliki URL yang mengandung parameter untuk mengakses data pengguna, seperti example.com/profile?id=123. Jika kontrol akses tidak diterapkan dengan benar, pengguna dapat mengganti nilai parameter id dengan nilai yang seharusnya tidak mereka akses, misalnya mengganti 123 dengan 124, dan dengan demikian mengakses profil pengguna lain.</p>
            <div class="box d-flex flex-column">
                <img src="../photos/idor.jpeg" width=750px>
            </div>
            <p class="paragraph">Dampak dari kerentanan IDOR dapat sangat serius, karena dapat memungkinkan penyerang untuk mengakses atau mengubah data sensitif, termasuk data pribadi pengguna, informasi keuangan, atau bahkan mengakses sistem atau sumber daya yang seharusnya terbatas aksesnya.</p>
            <p class="paragraph">Untuk melindungi aplikasi web dari kerentanan IDOR, penting untuk menerapkan kontrol akses yang kuat dan memastikan bahwa akses ke objek atau data hanya diberikan kepada pengguna yang memiliki hak akses yang sesuai. Ini dapat dilakukan dengan melakukan validasi pada setiap permintaan yang diajukan oleh pengguna, serta mengenkripsi atau menyamarkan identifikasi objek yang sensitif untuk mencegah manipulasi URL secara langsung.</p>

            <!-- Tombol "see more" expand untuk menampilkan kunci jawaban -->
            <button class="btn btn-primary mt-3" onclick="toggleAnswer()">Kunci Jawaban</button>
            <a href="../idor-edit-profile.php" class="btn btn-primary mt-3 ms-2">Latihan</a>
            <div id="answer" class="hide mt-3">
                <p class="paragraph">Kunci Jawaban:</p>
                <p class="paragraph">1. Buatlah 2 akun (attacker dan victim).</p>
                <p class="paragraph">2. Buka 2 tab pada browser firefox menggunakan multi-account container (attacker dan victim).</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/idor-1.png" width=750px>
                </div>
                <p class="paragraph">3. Klik "IDOR - Edit Profile" pada labs untuk kedua tab tersebut.</p>
                <p class="paragraph">4. Pada extension foxyproxy, pilih "burp" agar dapat menangkap request yang terkait. Pastikan burp suite sudah dibuka.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/idor-2.png" width=300px>
                </div>
                <p class="paragraph">5. Pada tab attacker, klik tombol update dan lihat "proxy history" pada burp suite.</p>
                <p class="paragraph">6. Pilih request dengan endpoint "POST /supermancyber/php/idor-update-profile.php" kemudian klik kanan pada request tersebut dan klik "send to repeater".</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/idor-3.png" width=750px>
                </div>
                <p class="paragraph">7. Buka repeater anda, dan modifikasi request tersebut dengan mengganti "userId" menjadi "userId" milik korban.</p>
                <p class="paragraph">8. Klik "send" untuk mengirim request dan nama korban akan terganti pada aplikasi.</p>
                <p class="paragraph">Pada skenario ini, "userId" hanyalah angka yang mudah ditebak sehingga eksploitasi IDOR ini sangat memungkinkan.</p>
                <p class="paragraph">Kerentanan IDOR ini dapat terjadi karena kurangnya validasi akses kontrol yang diterapkan.</p>
                <p class="paragraph">Berikut ini merupakan video POC untuk kerentanan IDOR:</p>
                <div class="box d-flex flex-column">
                    <video width="900px" controls>
                        <source src="../videos/idor.mp4" type="video/mp4">
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
