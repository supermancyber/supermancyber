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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Command Execution</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="shadow w-450 p-3 text-center" style="max-width: 1000px">
            <h3 style="font-size: 40px;" class="display-4">Command Execution</h3>
            <br>
                <form method="post" action="">
                    <label for="user_input">Input:</label>
                    <input type="text" name="user_input" id="user_input" required>
                    <button type="submit">Submit</button>
                </form>
                <br>
                <?php
                // Tangkap input pengguna jika ada
                $userInput = isset($_POST['user_input']) ? htmlspecialchars($_POST['user_input'], ENT_QUOTES, 'UTF-8') : '';
                
                // Tampilkan hasil jika ada input
                if ($userInput !== '') {
                    echo shell_exec($userInput);
                }
                ?>
                <br><br>
                <a href="labs.php" class="btn btn-secondary">Back</a>
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