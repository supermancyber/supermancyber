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
        <title>Broken Access Control</title>
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
            <h4 class="display-4">Broken Access Control</h4><br><br>
            <p class="paragraph">Broken access control adalah kerentanan keamanan pada aplikasi web yang terjadi ketika tidak ada atau terdapat kelemahan dalam implementasi kontrol akses yang tepat untuk mengatur siapa yang memiliki akses ke data atau fitur tertentu dalam aplikasi. Ini dapat memungkinkan pengguna untuk mendapatkan akses yang tidak sah ke informasi sensitif atau melakukan tindakan yang seharusnya hanya dapat dilakukan oleh pengguna dengan otorisasi yang sesuai.</p>
            <p class="paragraph">Pada dasarnya, setiap pengguna dalam aplikasi web memiliki peran atau tingkatan akses tertentu, misalnya admin, pengguna biasa, dan lain-lain. Kerentanan dalam kontrol akses dapat memungkinkan pengguna untuk menggunakan fitur yang seharusnya tidak diperbolehkan untuk peran mereka yang lebih rendah dari admin.</p>
            <div class="box d-flex flex-column">
                <img src="../photos/bac.png" width=600px>
            </div>
            <p class="paragraph">Menurut laporan dari platform bug bounty seperti HackerOne, Broken Access Control merupakan salah satu kerentanan yang paling umum dilaporkan oleh para peneliti keamanan. Hal ini menunjukkan bahwa kerentanan ini sangat signifikan dan tersebar luas di berbagai jenis aplikasi web, mulai dari aplikasi e-commerce hingga platform media sosial.</p>
            <p class="paragraph">Kerentanan dalam kontrol akses, atau yang sering disebut sebagai "broken access control", dapat memiliki dampak yang serius terhadap keamanan aplikasi web. Berikut adalah beberapa dampak dari broken access control:</p>
            <p class="paragraph">1. Akses Tidak Sah: Penyerang dapat memperoleh akses yang tidak sah ke fitur, data, atau fungsi yang seharusnya tidak mereka miliki. Hal ini bisa berarti pengguna mendapatkan hak istimewa yang seharusnya hanya dimiliki oleh pengguna lain atau administrator.</p>
            <p class="paragraph">2. Pencurian Data Sensitif: Jika kontrol akses rusak, penyerang dapat memanfaatkannya untuk mengakses data sensitif, seperti informasi pengguna, informasi keuangan, atau data rahasia lainnya yang seharusnya hanya dapat diakses oleh pihak yang berwenang.</p>
            <p class="paragraph">3. Manipulasi Data: Penyerang dapat menggunakan kerentanan dalam kontrol akses untuk memanipulasi data di dalam aplikasi. Ini dapat berarti mengubah atau menghapus data yang ada, atau bahkan menyisipkan data palsu atau berbahaya.</p>
            <p class="paragraph">Oleh karena itu, melakukan pengujian keamanan untuk mendeteksi kerentanan dalam kontrol akses, seperti Broken Access Control, sangat penting bagi organisasi yang mengelola aplikasi web semacam itu. Dengan melakukan pengujian tersebut, mereka dapat mengidentifikasi dan memperbaiki celah keamanan sebelum penyerang dapat mengeksploitasi mereka untuk tujuan yang merugikan.</p>
            <!-- Tombol "see more" expand untuk menampilkan kunci jawaban -->
            <button class="btn btn-primary mt-3" onclick="toggleAnswer()">Kunci Jawaban Lab</button>
            <div id="answer" class="hide mt-3">
                <p class="paragraph">Kunci Jawaban:</p>
                <p class="paragraph">1. Buat 2 akun (attacker dan victim).</p>
                <p class="paragraph">2. Buka 2 tab pada firefox menggunakan multi-account container (attacker dan victim).</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/bac-1.png" width=750px>
                </div>
                <p class="paragraph">3. Klik "groups" pada labs untuk kedua tab tersebut.</p>
                <p class="paragraph">4. Pada tab victim, buatlah group bernama "victim's group" agar tidak bingung.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/bac-2.png" width=750px>
                </div>
                <p class="paragraph">5. Masih di tab victim, klik "read" untuk melihat detail dari group tersebut.</p>
                <p class="paragraph">6. Bisa dilihat bahwa victim merupakan admin dari group tersebut.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/bac-3.png" width=750px>
                </div>
                <p class="paragraph">7. Klik "Add user to group" dan tambahkan attacker sebagai member dari group tersebut. Untuk mendapatkan user ID dari attacker, bisa dilihat di database.</p>
                <p class="paragraph">8. Setelah berhasil menambahkan attacker ke dalam "victim's group", pindah ke tab attacker dan refresh halamannya.</p>
                <p class="paragraph">9. Pada tab attacker, klik "read" untuk "victim's group".</p>
                <p class="paragraph">10. Perhatikan bahwa dalam sudut pandang attacker, tidak terdapat tombol "change role", "delete", dan juga "add user to group". Ini berarti bahwa role "member" tidak dapat mengakses fitur tersebut.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/bac-4.png" width=750px>
                </div>
                <p class="paragraph">11. Sebagai attacker, pertama catat terlebih dahulu group id dari victim (bisa didapatkan dari URL pada halaman group details). Group ID ini akan diperlukan di langkah berikutnya.</p>
                <p class="paragraph">12. Masih di tab attacker, buatlah group bernama "attacker's group".</p>
                <p class="paragraph">13. Kemudian undang siapapun ke dalam "attacker's group".</p>
                <p class="paragraph">14. Nyalakan "burp" pada foxyproxy dan pastikan sudah membuka burp suite.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/bac-5.png" width=300px>
                </div>
                <p class="paragraph">15. Sebagai attacker, ganti role suatu user dalam "attacker's group" dari "member" menjadi "admin" dan tangkap request nya.</p>
                <p class="paragraph">16. Untuk menangkap request tersebut, cari request dengan endpoint "POST /supermancyber/php/update-role.php" pada burp suite proxy history.</p>
                <p class="paragraph">17. Setelah menemukan request tersebut, klik kanan -> "send to repeater".</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/bac-6.png" width=750px>
                </div>
                <p class="paragraph">18. Ubah "group_id" menjadi "group_id" dari "victim's group" (didapatkan dari step 11).</p>
                <p class="paragraph">19. Klik "send" untuk mengirim request tersebut dan attacker sekarang sudah menjadi admin dari "victim's group".</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/bac-7.png" width=750px>
                </div>
                <b style="display: block; text-align: left; font-size: 28px; margin-bottom:14px;">NOTE</b>
                <p class="paragraph">Kerentanan ini berlaku untuk semua fitur dalam groups, diantara lain yaitu:</p>
                <ul>
                    <li><p class="paragraph">Update Group</p></li>
                    <li><p class="paragraph">Delete Group</p></li>
                    <li><p class="paragraph">Add User to Group</p></li>
                    <li><p class="paragraph">Remove User in Group</p></li>
                    <li><p class="paragraph">Clear Chat pada fitur group chat</p></li>
                </ul>
                <p class="paragraph">Kerentanan ini sangat umum pada aplikasi web dan harus diperhatikan terutama untuk website yang terdapat banyak peran untuk pengguna.</p>
                <p class="paragraph">Berikut ini merupakan video POC untuk kerentanan BAC:</p>
                <div class="box d-flex flex-column">
                    <video width="900px" controls>
                        <source src="../videos/bac.mp4" type="video/mp4">
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
