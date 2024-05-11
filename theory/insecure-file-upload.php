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
        <title>Insecure File Upload</title>
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
            <h4 class="display-4">Insecure File Upload</h4><br><br>
            <p class="paragraph">Insecure File Upload (IFU) adalah kerentanan yang memungkinkan pengguna untuk mengunggah dan mengeksekusi file berbahaya ke server yang rentan. Hal ini dapat menyebabkan sejumlah serangan, termasuk:</p>
            <p class="paragraph">1. Penyisipan Shell: Penyerang dapat mengunggah shell backdoor ke server yang rentan, memungkinkan akses penuh ke sistem.</p>
            <p class="paragraph">2. Penyisipan Malware: File berbahaya seperti malware atau ransomware dapat diunggah dan dieksekusi di server, membahayakan keamanan data dan integritas sistem.</p>
            <p class="paragraph">3. Penyerangan Pengguna: File-file berbahaya seperti JavaScript atau HTML dapat diunggah dan dieksekusi di browser pengguna lainnya, mengarah pada serangan XSS atau serangan lainnya.</p>
            <div class="box d-flex flex-column">
                <img src="../photos/insecure-file-upload.png" width=500px>
            </div>
            <p class="paragraph">Untuk mencegah serangan Insecure File Upload, penting untuk melakukan validasi dan filtrasi yang ketat terhadap tipe file yang diizinkan untuk diunggah, membatasi ukuran file, serta menyimpan file di lokasi yang aman yang tidak dapat dieksekusi secara langsung oleh server. Selain itu, mengimplementasikan mekanisme deteksi dan pemindaian malware juga dapat membantu dalam melindungi aplikasi dari serangan Insecure File Upload.</p>
            <!-- Tombol "see more" expand untuk menampilkan kunci jawaban -->
            <button class="btn btn-primary mt-3" onclick="toggleAnswer()">Kunci Jawaban Lab</button>
            <div id="answer" class="hide mt-3">
                <p class="paragraph">Kunci Jawaban:</p>
                <p class="paragraph">1. Klik "Insecure File Upload" pada labs.</p>
                <p class="paragraph">2. Klik "browse" dan perhatikan bahwa tidak ada file extension yang dibatasi, sehingga penyerang dapat menyisipkan file-file berbahaya ke dalam server.</p>
                <p class="paragraph">3. Sebagai contoh, file svg yang mengandung skrip berhasil diunggah dan terdapat path-nya.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/insecure-file-upload-1.png" width=750px>
                </div>
                <p class="paragraph">4. Ketika file tersebut dibuka sesuai dengan path, maka skrip tersebut tereksekusi.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/insecure-file-upload-2.png" width=750px>
                </div>
                <p class="paragraph">5. Jika dikirim ke korban, ini dapat memiliki dampak yang merugikan karena skrip berbahaya dapat dieksekusi.</p>
                <p class="paragraph">Tidak hanya itu, file php yang mengandung perintah untuk mengirim shell juga dapat diunggah sehingga memungkinkan penyerang untuk mengambil alih server.</p>
                <p class="paragraph">Kerentanan ini menjadi pengingat bagi para developer untuk memfilter file extension yang diizinkan untuk diunggah.</p>
                <p class="paragraph">Berikut ini merupakan video POC untuk kerentanan insecure file upload:</p>
                <div class="box d-flex flex-column">
                    <video width="900px" controls>
                        <source src="../videos/insecure-file-upload.mp4" type="video/mp4">
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
