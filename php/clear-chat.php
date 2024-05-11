<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname']) && isset($_SESSION['group_id'])) {
    // Sertakan kode koneksi ke database di sini

    $host = 'localhost';
    $dbname = 'supermancyber';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ambil ID grup dari sesi
        $groupId = $_SESSION['group_id'];

        // Query untuk menghapus semua pesan dalam obrolan grup
        $query = "DELETE FROM chat_messages WHERE group_id = :group_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':group_id', $groupId);
        $stmt->execute();

        // Redirect back to group chat page after clearing chat
        header("Location: ../group-chat.php?group_id=$groupId");
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna tidak masuk atau data sesi tidak lengkap, arahkan kembali ke halaman sebelumnya
    header("Location: ../index.php");
    exit;
}
?>
