<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname']) && isset($_POST['gname'])) {
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
        
        // Ambil nama grup dari formulir
        $groupName = $_POST['gname'];

        // Query untuk menyimpan grup ke database
        $query = "INSERT INTO groups (group_name) VALUES (:group_name)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':group_name', $groupName);
        $stmt->execute();
        
        // Ambil ID grup yang baru saja dibuat
        $groupId = $pdo->lastInsertId();

        // Tambahkan pengguna ke grup sebagai admin
        $isAdmin = true; // Set is_admin ke true secara langsung
        $query = "INSERT INTO group_members (user_id, group_id, is_admin) VALUES (:user_id, :group_id, :is_admin)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':group_id', $groupId);
        $stmt->bindParam(':is_admin', $isAdmin, PDO::PARAM_BOOL); // Bind parameter sebagai boolean
        $stmt->execute();

        // Alihkan kembali ke halaman beranda setelah menambahkan grup
        header("Location: ../groups.php");
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna tidak masuk atau data form tidak lengkap, arahkan kembali ke halaman login
    header("Location: ../index.php");
    exit;
}
?>
