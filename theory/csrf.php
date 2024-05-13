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
            #answer {
                text-align: left;
            }
        </style>
    </head>
    <body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="shadow p-3 text-center box"> <!-- Tambahkan kelas box -->
            <h4 class="display-4">Cross Site Request Forgery (CSRF)</h4><br><br>
            <p class="paragraph">CSRF adalah serangan keamanan web yang bertujuan untuk memanfaatkan sesi yang sudah diautentikasi dari pengguna untuk melakukan tindakan yang tidak diinginkan tanpa pengetahuan mereka.</p>
            <p class="paragraph">Contohnya, seorang penyerang dapat membuat situs web palsu yang berisi formulir yang secara otomatis mengirim permintaan ke situs yang ditargetkan. Jika pengguna yang sudah diautentikasi mengunjungi situs palsu tersebut, permintaan yang dipicu oleh formulir tersebut akan dikirim dengan kredensial pengguna, memungkinkan penyerang untuk melakukan tindakan yang tidak diinginkan.</p>
            <div class="box d-flex flex-column">
                <img src="../photos/csrf.png" width=750px>
            </div>
            <p class="paragraph">Untuk melindungi aplikasi web dari serangan CSRF, penting untuk menerapkan mekanisme validasi dan token keamanan yang unik untuk setiap permintaan yang diajukan oleh pengguna.</p>
            
            <!-- Tombol "see more" expand untuk menampilkan kunci jawaban -->
            <button class="btn btn-primary mt-3" onclick="toggleAnswer()">Kunci Jawaban</button>
            <a href="../csrf-edit-profile.php" class="btn btn-primary mt-3 ms-2">Latihan</a>
            <div id="answer" class="hide mt-3">
                <p class="paragraph">Kunci Jawaban:</p>
                <p class="paragraph">1. Buat 2 akun (attacker dan victim).</p>
                <p class="paragraph">2. Buka 2 tab pada browser firefox menggunakan multi-account container (attacker dan victim).</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/csrf-1.png" width=750px>
                </div>
                <p class="paragraph">3. Buka "CSRF - Edit Profile" pada labs untuk kedua tab tersebut.</p>
                <p class="paragraph">4. Pada extension foxyproxy, pilih "burp" sebagai proxy. Pastikan burp suite sudah dibuka.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/csrf-2.png" width=300px>
                </div>
                <p class="paragraph">5. Pada tab attacker, klik tombol update dan lihat "proxy history" pada burp suite.</p>
                <p class="paragraph">6. Pilih request dengan endpoint "POST /supermancyber/php/csrf-update-profile.php".</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/csrf-3.png" width=750px>
                </div>
                <p class="paragraph">7. Apabila menggunakan burp suite professional, langsung klik kanan pada request tersebut -> engagement tools -> generate csrf poc dan kirim ke korban.</p>
                <p class="paragraph">8. Apabila menggunakan burp suite community edition, gunakan tools untuk generate csrf poc. Salah satu contohnya ada di https://tools.nakanosec.com/csrf.</p>
                <p class="paragraph">9. Copy request tersebut pada tools dan klik "generate csrf poc".</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/csrf-4.png" width=750px>
                </div>
                <p class="paragraph">10. Hasil dari html tersebut dapat dikirim ke korban.</p>
                <p class="paragraph">11. Ketika korban mengklik tombol tersebut, maka nama mereka pada aplikasi akan terganti.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/csrf-5.png" width=750px>
                </div>
                <p class="paragraph">Kerentanan CSRF ini dapat terjadi karena kurangnya validasi ketika mengirim request yang berasal dari bukan situs resmi.</p>
                <p class="paragraph">Mitigasinya adalah dengan menambah CSRF token sebagai validasi apakah request benar-benar berasal dari situs resmi atau tidak.</p>
                <p class="paragraph">Jika anda mengalami kesulitan untuk generate csrf poc, anda dapat menggunakan "csrf.html" yang sudah tersedia pada folder aplikasi ini.</p>
                <p class="paragraph">Berikut ini merupakan video POC untuk kerentanan CSRF:</p>
                <div class="box d-flex flex-column">
                    <video width="900px" controls>
                        <source src="../videos/csrf.mp4" type="video/mp4">
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
