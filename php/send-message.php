<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname']) && isset($_POST['message']) && isset($_SESSION['group_id'])) {
    // Sertakan kode koneksi ke database di sini

    $host = 'localhost';
    $dbname = 'supermancyber';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ambil ID pengguna dari sesi
        $userId = $_SESSION['id'];

        // Ambil ID grup dari sesi
        $groupId = $_SESSION['group_id'];

        // Ambil pesan dari formulir
        $message = $_POST['message'];

        // Query untuk menyimpan pesan ke database
        $query = "INSERT INTO chat_messages (user_id, group_id, message, sender) VALUES (:user_id, :group_id, :message, :sender)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':group_id', $groupId);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':sender', $_SESSION['fname']); // Menambahkan sender dengan nama pengguna yang sedang masuk
        $stmt->execute();

        // Alihkan kembali ke halaman grup setelah mengirim pesan
        header("Location: ../group-chat.php?group_id=$groupId");
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna tidak masuk atau data form tidak lengkap, arahkan kembali ke halaman sebelumnya
    header("Location: ../index.php");
    exit;
}
?>
