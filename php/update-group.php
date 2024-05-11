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

        // Ambil data yang dikirimkan melalui metode POST
        $groupId = $_POST['groupId'];
        $group_name = $_POST['group_name'];

        // Update informasi grup dalam database
        $query = "UPDATE groups SET group_name = :group_name WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':group_name', $group_name);
        $stmt->bindParam(':id', $groupId);
        $stmt->execute();

        // Redirect ke halaman grup setelah pembaruan berhasil
        header("Location: ../groups.php");
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>
