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
        <title>Labs</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            
        </style>
    </head>
    <body>
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="shadow w-450 p-3 text-center d-flex flex-column">
                <h3 class="display-4">Labs</h3>
                <a href="csrf-edit-profile.php" class="btn btn-primary btn-block mb-3">CSRF - Edit Profile</a>
                <a href="idor-edit-profile.php" class="btn btn-primary btn-block mb-3">IDOR - Edit Profile</a>
                <a href="reflected-xss.php" class="btn btn-primary btn-block mb-3">Reflected XSS</a>
                <a href="command-exec.php" class="btn btn-primary btn-block mb-3">Command Execution</a>
                <a href="local-file-inclusion.php" class="btn btn-primary btn-block mb-3">Local File Inclusion</a>
                <a href="insecure-file-upload.php" class="btn btn-primary btn-block mb-3">Insecure File Upload</a>
                <a href="ssrf.php" class="btn btn-primary btn-block mb-3">Server Side Request Forgery</a>
                <a href="business-logic-error.php" class="btn btn-primary btn-block mb-3">Business Logic Error</a>
                <a href="groups.php" class="btn btn-info btn-block mb-3">Groups</a>
                <a href="theory.php" class="btn btn-warning btn-block mb-3">Theory</a>
                <a href="help.php" class="btn btn-secondary btn-block mb-3">Help</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
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
