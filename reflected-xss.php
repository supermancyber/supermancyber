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
    
    // Tampilkan formulir reflected xss
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Reflected XSS</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="shadow w-450 p-3 text-center">
                <h3 style="font-size: 40px;" class="display-4">Reflected XSS</h3><br>
                <!-- Tampilkan formulir dengan data pengguna -->
                <form method="GET" action="" class="row align-items-center">
                    <div class="col">
                        <div class="input-group mb-3">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search...">
                            <button type="submit" class="btn btn-primary" style="margin-left: 5px;">Search</button>
                        </div>
                    </div>
                </form>
                <br>
                <?php
                // Check if the search parameter is set
                if (isset($_GET['search'])) {
                    // Display the search query without sanitization
                    echo "<p>You searched for: " . $_GET['search'] . "</p>";
                }
                // // Check if the search parameter is set
                // if (isset($_GET['search'])) {
                //     // Sanitize the search query before displaying
                //     $searchQuery = htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8');
                //     echo "<p>You searched for: " . $searchQuery . "</p>";
                // }
                ?>
                <br><a href="labs.php" class="btn btn-secondary">Back</a>
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
