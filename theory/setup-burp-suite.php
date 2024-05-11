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
        <title>Setup Burp Suite</title>
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
        </style>
    </head>
    <body>
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="shadow p-3 text-center box"> <!-- Tambahkan kelas box -->
                <h4 class="display-4">Setup Burp Suite</h4><br><br>
                <p class="paragraph">Burp Suite adalah salah satu tools proxy yang digunakan untuk menangkap request dan response dari aplikasi web maupun mobile. Berikut ini merupakan ilustrasi dari proxy:</p>
                <img src="../photos/proxy.png" width=750px>
                <p class="paragraph">Pada lab ini, tools tersebut akan digunakan untuk mencari kerentanan broken access control.</p>
                <p class="paragraph">Berikut ini merupakan langkah-langkah untuk melakukan instalasi dan setup burp suite:</p>
                <p class="paragraph">1. Pastikan sudah menjalankan aplikasi web ini di localhost dan sudah selesai melakukan import SQL.</p>
                <p class="paragraph">2. Silahkan download burp suite terlebih dahulu <a href="https://portswigger.net/burp/communitydownload" target="_blank">disini</a> untuk burp suite community edition.</p>
                <p class="paragraph">3. Setelah berhasil download burp suite, jalankan aplikasi tersebut.</p>
                <p class="paragraph">4. Langsung klik "next", kemudian "start burp".</p>
                <p class="paragraph">5. Secara default, setting proxy pada burp suite adalah 127.0.0.1:8080.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/proxy-listener.png" width=750px>
                </div>
                <p class="paragraph">6. Buka settings di browser firefox, kemudian search "proxy". Disarankan untuk menggunakan firefox karena terdapat extension yang dapat membantu dalam melakukan pentesting.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/search-proxy.png" width=500px>
                </div>
                <p class="paragraph">7. Pada network settings di firefox, pilih "manual configuration" dan set http proxy menjadi 127.0.0.1 dengan port 8080. Jangan lupa click "OK".</p>
                <div class="d-flex flex-column">
                    <img src="../photos/firefox-proxy-settings.png" width=500px>
                </div><br>
                <p class="paragraph">8. Pada browser firefox, buka http://burp dan klik "CA Certificate" untuk download burp certificate.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/download-burp-certificate.png" width=750px>
                </div>
                <p class="paragraph">9. Kemudian, kembali ke settings firefox dan search "certificate".</p>
                <p class="paragraph">10. Klik "view certificate" dan import burp certificate yang baru saja didownload. Jangan lupa click "OK" jika sudah.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/import-burp-certificate.png" width=500px>
                </div>
                <p class="paragraph">11. Selanjutnya download extension yang diperlukan dan membantu dalam pentesting, yaitu foxyproxy dan multi-account container.</p>
                <p class="paragraph">12. Silahkan buka <a href="https://addons.mozilla.org/id/firefox/addon/foxyproxy-standard" target="_blank">https://addons.mozilla.org/id/firefox/addon/foxyproxy-standard</a> dan tambahkan extension foxyproxy ke firefox.</p>
                <p class="paragraph">13. Setelah berhasil menambahkan foxyproxy ke firefox, klik "options" pada foxyproxy.</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/options-foxyproxy.png" width=500px>
                </div>
                <p class="paragraph">14. Pilih menu "proxies", kemudian klik tombol "add".</p>
                <p class="paragraph">15. Atur hostname menjadi 127.0.0.1 dan port 8080. Silahkan namakan "burp" untuk title tersebut. Jangan lupa klik "save".</p>
                <div class="box d-flex flex-column">
                    <img src="../photos/setup-foxyproxy.png" width=750px>
                </div>
                <p class="paragraph">16. Pada proxy setting firefox, pilih "use system proxy settings" dan klik "OK".</p>
                <div class="d-flex flex-column">
                    <img src="../photos/firefox-proxy-settings(2).png" width=500px>
                </div><br>
                <p class="paragraph">17. Extension foxyproxy sudah dapat digunakan untuk setting proxy cukup dengan klik "burp" dan tidak perlu ke settings firefox lagi.</p>
                <div class="d-flex flex-column">
                    <img src="../photos/foxyproxy.png" width=300px>
                </div><br>
                <p class="paragraph">18. Berikutnya, buka <a href="https://addons.mozilla.org/id/firefox/addon/multi-account-containers" target="_blank">https://addons.mozilla.org/id/firefox/addon/multi-account-containers</a> dan tambahkan extension multi-account container ke firefox.</p>
                <p class="paragraph">19. Untuk menambahkan container, klik extension multi-account container pada kanan atas, kemudian klik "manage container" -> "+ new container".</p>
                <p class="paragraph">20. Tambahkan dua container: satu untuk "attacker" dan satunya lagi untuk "victim".</p>
                <p class="paragraph">21. Extension multi-account container sudah dapat digunakan untuk membedakan attacker dan victim dalam satu browser.</p>
                <div class="d-flex flex-column">
                    <img src="../photos/multi-account-container.png" width=300px>
                </div>
                <br><br><a href="../theory.php" class="btn btn-secondary">Back</a>
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
