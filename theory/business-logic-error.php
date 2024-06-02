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
        <title>Business Logic Error</title>
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
            <h4 class="display-4">Business Logic Error</h4><br><br>
            <p class="paragraph">Business Logic Error adalah jenis kerentanan yang terjadi ketika terdapat kelemahan dalam logika bisnis sebuah aplikasi web yang dapat dimanipulasi oleh pengguna untuk mencapai hasil yang tidak diinginkan atau merugikan. Kerentanan ini terjadi ketika pengembang tidak memperhitungkan atau mengimplementasikan dengan benar logika bisnis yang tepat dalam aplikasi web mereka.</p>
            <div class="box d-flex flex-column">
                <img src="../photos/business-logic-error.png" width=500px>
            </div>
            <p class="paragraph">Dampak dari Business Logic Error bisa beragam tergantung pada jenis aplikasi dan logika bisnis yang terlibat. Beberapa contoh dampaknya termasuk:</p>
            <p class="paragraph">1. Keuangan: Kesalahan dalam logika bisnis aplikasi keuangan dapat menyebabkan kerugian finansial yang signifikan bagi perusahaan atau pengguna.</p>
            <p class="paragraph">2. Keamanan: Kesalahan dalam logika bisnis yang berkaitan dengan otorisasi atau kontrol akses dapat menyebabkan akses tidak sah ke data sensitif atau fungsionalitas yang terlindungi.</p>
            <p class="paragraph">3. Integritas Data: Kesalahan dalam logika bisnis dapat mengakibatkan kehilangan atau kerusakan data yang signifikan, baik secara sengaja maupun tidak sengaja.</p>
            <p class="paragraph">4. Reputasi: Kerentanan terhadap Business Logic Error dapat merusak reputasi perusahaan dengan menghasilkan pengalaman pengguna yang buruk atau menimbulkan kebocoran data yang merugikan.</p>
            <p class="paragraph">Untuk mencegah Business Logic Error, penting untuk melakukan pengujian yang menyeluruh pada semua skenario logika bisnis yang mungkin, baik yang diinginkan maupun yang tidak diinginkan. Selain itu, pengembang perlu memperhatikan keamanan secara menyeluruh dalam merancang dan mengimplementasikan logika bisnis aplikasi, termasuk validasi input, otorisasi yang ketat, dan penggunaan prinsip-prinsip keamanan yang baik dalam pengembangan perangkat lunak. Selalu memperbarui dan memantau aplikasi untuk mendeteksi dan memperbaiki kerentanan juga merupakan praktik yang sangat penting.</p>
            <!-- Tombol "see more" expand untuk menampilkan kunci jawaban -->
            <button class="btn btn-primary mt-3" onclick="toggleAnswer()">Kunci Jawaban</button>
            <a href="../business-logic-error.php" class="btn btn-primary mt-3 ms-2">Latihan</a>
            <div id="answer" class="hide mt-3">
                <p class="paragraph">Kunci Jawaban:</p>
                <p class="paragraph">1. Klik "Business Logic Error" pada labs.</p>
                <p class="paragraph">2. Pilih "burp" pada extension foxyproxy. Pastikan burp suite sudah dibuka.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/business-logic-error-1.png" width=300px>
                </div>
                <p class="paragraph">3. Klik tombol "add to cart" untuk produk B satu kali.</p>
                <p class="paragraph">4. Pada burp suite, nyalakan "intercept" di bagian proxy.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/business-logic-error-2.png" width=750px>
                </div>
                <p class="paragraph">5. Balik ke firefox dan klik "add to cart" untuk produk A.</p>
                <p class="paragraph">6. Perhatikan bahwa terdapat request di burp suite yang kita sedang intercept.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/business-logic-error-3.png" width=750px>
                </div>
                <p class="paragraph">7. Ganti value "quantity" dari "1" menjadi "-2".</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/business-logic-error-4.png" width=750px>
                </div>
                <p class="paragraph">8. Matikan intercept pada burp suite dan kembali ke firefox.</p>
                <p class="paragraph">9. Bisa dilihat bahwa quantity dari produk A menjadi minus dan menyebabkan harga juga ikutan minus.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/business-logic-error-5.png" width=750px>
                </div>
                <p class="paragraph">Kerentanan seperti ini dapat menyebabkan kerugian finansial sehingga harus diperhatikan dengan baik.</p>
                <p class="paragraph">Berikut ini merupakan video POC untuk kerentanan business logic error:</p>
                <div class="box d-flex flex-column">
                    <video width="900px" controls>
                        <source src="../videos/business-logic-error.mp4" type="video/mp4">
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
