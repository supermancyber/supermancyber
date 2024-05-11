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
        $userId = $_POST['user_id'];
        $isAdmin = $_POST['is_admin'];
        $groupId = $_POST['group_id'];

        // // Periksa apakah pengguna yang sedang masuk adalah admin dari grup tersebut
        // $queryAdminCheck = "SELECT is_admin FROM group_members WHERE group_id = :group_id AND user_id = :user_id";
        // $stmtAdminCheck = $pdo->prepare($queryAdminCheck);
        // $stmtAdminCheck->bindParam(':group_id', $groupId);
        // $stmtAdminCheck->bindParam(':user_id', $_SESSION['id']);
        // $stmtAdminCheck->execute();
        // $isAdminGroup = $stmtAdminCheck->fetchColumn();

        // if ($isAdminGroup) {
        //     // Update peran pengguna dalam grup
        //     $query = "UPDATE group_members SET is_admin = :is_admin WHERE user_id = :user_id AND group_id = :group_id";
        //     $stmt = $pdo->prepare($query);
        //     $stmt->bindParam(':is_admin', $isAdmin);
        //     $stmt->bindParam(':user_id', $userId);
        //     $stmt->bindParam(':group_id', $groupId);
        //     $stmt->execute();

        //     // Set $_SESSION['group_id'] sebelum melakukan perubahan peran pengguna
        //     $_SESSION['group_id'] = $groupId;

        //     // Redirect kembali ke halaman detail grup setelah peran berhasil diperbarui
        //     header("Location: ../group-details.php?group_id=$groupId");
        //     exit;
        // } else {
        //     // Jika pengguna yang sedang masuk bukan admin grup, kembali ke halaman sebelumnya
        //     header("Location: ../groups.php");
        //     exit;
        // }

        // Update peran pengguna dalam grup
        $query = "UPDATE group_members SET is_admin = :is_admin WHERE user_id = :user_id AND group_id = :group_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':is_admin', $isAdmin);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':group_id', $groupId);
        $stmt->execute();

        // Set $_SESSION['group_id'] sebelum melakukan perubahan peran pengguna
        $_SESSION['group_id'] = $groupId;

        // Redirect kembali ke halaman detail grup setelah peran berhasil diperbarui
        header("Location: ../group-details.php?group_id=$groupId");
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna belum masuk, redirect ke halaman login
    header("Location: ../index.php");
    exit;
}
?>

