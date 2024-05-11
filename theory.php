<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    try {
        // Sertakan koneksi ke database di sini
        $host = 'localhost';
        $dbname = 'supermancyber';
        $username = 'root';
        $password = '';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query untuk mendapatkan data pengguna
        $userId = $_SESSION['id'];
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Tangkap kesalahan koneksi database
        echo "Error: " . $e->getMessage();
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Theory</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="shadow w-450 p-3 text-center d-flex flex-column">
                <h3 class="display-4">Theory</h3>
                <a href="theory/csrf.php" class="btn btn-primary btn-block mb-3">Cross Site Request Forgery</a>
                <a href="theory/idor.php" class="btn btn-primary btn-block mb-3">Insecure Direct Object Reference</a>
                <a href="theory/xss.php" class="btn btn-primary btn-block mb-3">Cross Site Scripting</a>
                <a href="theory/command-execution.php" class="btn btn-primary btn-block mb-3">Command Execution</a>
                <a href="theory/lfi.php" class="btn btn-primary btn-block mb-3">Local File Inclusion</a>
                <a href="theory/insecure-file-upload.php" class="btn btn-primary btn-block mb-3">Insecure File Upload</a>
                <a href="theory/ssrf.php" class="btn btn-primary btn-block mb-3">Server Side Request Forgery</a>
                <a href="theory/business-logic-error.php" class="btn btn-primary btn-block mb-3">Business Logic Error</a>
                <a href="theory/bac.php" class="btn btn-primary btn-block mb-3">Broken Access Control</a>
                <a href="theory/setup-burp-suite.php" class="btn btn-info btn-block mb-3">Setup Burp Suite</a>
                <a href="labs.php" class="btn btn-warning btn-block mb-3">Labs</a>
                <a href="help.php" class="btn btn-secondary btn-block mb-3">Help</a>
                <a href="logout.php" class="btn btn-danger btn-block">Logout</a>
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
