<?php
session_start();

// Isi dengan informasi koneksi database Anda
$host = 'localhost';
$dbname = 'supermancyber';
$username = 'root';
$password = '';

try {
    // Koneksi ke database menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_SESSION['fname']) && isset($_POST['fname'])) {
        $userId = $_SESSION['id'];
        $newFname = $_POST['fname'];

        // Query untuk memperbarui data pengguna berdasarkan ID
        $query = "UPDATE users SET fname = :fname WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':fname', $newFname);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        // Alihkan kembali ke halaman beranda setelah memperbarui
        header("Location: ../labs.php");
        exit;
    } else {
        // Jika data sesi atau formulir tidak lengkap, alihkan ke halaman login
        header("Location: ../index.php");
        exit;
    }
} catch (PDOException $e) {
    // Tangkap kesalahan koneksi database
    echo "Error: " . $e->getMessage();
    exit;
}
?>