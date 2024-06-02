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
        <title>XSS</title>
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
            <h4 class="display-4">Cross Site Scripting (XSS)</h4><br><br>
            <p class="paragraph">Cross-Site Scripting (XSS) adalah sebuah serangan keamanan pada aplikasi web yang memungkinkan penyerang menyisipkan skrip berbahaya ke dalam halaman web yang dilihat oleh pengguna lain. Serangan ini terjadi ketika aplikasi web tidak memvalidasi atau menyaring input yang diterima dari pengguna dan kemudian menyertakan input tersebut dalam halaman web tanpa melakukan escapting atau encoding yang benar.</p>
            <div class="box d-flex flex-column">
                <img src="../photos/xss.png" width=750px>
            </div>
            <p class="paragraph">Ada dua jenis XSS utama: Reflected XSS dan Stored XSS.</p>
            <p class="paragraph">1. Reflected XSS: Serangan Reflected XSS terjadi ketika skrip berbahaya disisipkan ke dalam halaman web sebagai bagian dari permintaan HTTP yang dikirimkan oleh pengguna. Hal ini kemudian direfleksikan kembali oleh aplikasi web ke pengguna lain, biasanya dalam bentuk pesan kesalahan atau data yang disertakan dalam respons halaman. Contoh umum serangan ini adalah ketika seorang penyerang membuat tautan yang mengandung skrip berbahaya dan meminta pengguna untuk mengklik tautan tersebut, yang kemudian menyebabkan eksekusi skrip pada browser pengguna yang mengkliknya.</p>
            <p class="paragraph">2. Stored XSS: Serangan Stored XSS terjadi ketika skrip berbahaya disisipkan ke dalam aplikasi web dan disimpan di server. Skrip ini kemudian akan dieksekusi setiap kali halaman yang mengandung skrip tersebut dimuat oleh pengguna lain. Contoh umum serangan ini adalah ketika penyerang mengirimkan pesan atau memposting komentar yang mengandung skrip berbahaya ke dalam aplikasi web, yang kemudian ditampilkan kepada pengguna lain, yang akan mengeksekusi skrip tersebut saat melihat pesan atau komentar tersebut.</p>
            <p class="paragraph">Dampak dari serangan XSS bisa sangat berbahaya, termasuk pencurian kredensial pengguna, peretasan sesi pengguna, pengalihan pengguna ke situs phishing, manipulasi atau penghapusan data, dan bahkan pengendalian penuh atas akun pengguna.</p>
            <p class="paragraph">Untuk melindungi aplikasi web dari serangan XSS, penting untuk memvalidasi dan menyaring setiap input yang diterima dari pengguna, serta melakukan escapting atau encoding pada data yang disertakan dalam halaman web untuk memastikan bahwa skrip berbahaya tidak dapat dieksekusi. Selain itu, menggunakan kebijakan keamanan seperti Content Security Policy (CSP) juga bisa membantu mencegah serangan XSS dengan membatasi sumber daya yang diizinkan untuk dimuat pada halaman web.</p>
            <!-- Tombol "see more" expand untuk menampilkan kunci jawaban -->
            <button class="btn btn-primary mt-3" onclick="toggleAnswer()">Kunci Jawaban</button>
            <a href="../reflected-xss.php" class="btn btn-primary mt-3 ms-2">Latihan</a>
            <div id="answer" class="hide mt-3">
                <p class="paragraph">Kunci Jawaban:</p>
                <p class="paragraph">1. Buat 2 akun (attacker dan victim).</p>
                <p class="paragraph">2. Buka 2 tab pada browser firefox menggunakan multi-account container (attacker dan victim).</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/xss-1.png" width=750px>
                </div>
                <p class="paragraph">3. Klik "Reflected XSS" pada labs untuk kedua tab tersebut.</p>
                <p class="paragraph">4. Pada tab attacker, ketik apapun dan klik tombol "search".</p>
                <p class="paragraph">5. Bisa kita lihat bahwa apa yang kita ketik direfleksikan kembali pada aplikasi.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/xss-2.png" width=750px>
                </div>
                <p class="paragraph">6. Ketik payload XSS apapun dalam kolom search, misal "<code>&lt;img src=x onerror=alert(1)&gt;</code>".</p>
                <p class="paragraph">7. Klik tombol "search" dan bisa kita lihat bahwa payload XSS tersebut tereksekusi karena kurangnya validasi tag HTML.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/xss-3.png" width=750px>
                </div>
                <p class="paragraph">8. Copy link pada tab attacker dan kirim link tersebut ke korban agar skrip tersebut tereksekusi pada korban.</p>
                <p class="paragraph">Kerentanan XSS ini berbahaya karena dapat digunakan untuk mencuri cookie dari korban sehingga attacker dapat mengambil alih akun milik korban.</p>
                <p class="paragraph">Berikut ini merupakan video POC untuk kerentanan reflected XSS:</p>
                <div class="box d-flex flex-column">
                    <video width="900px" controls>
                        <source src="../videos/xss.mp4" type="video/mp4">
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
