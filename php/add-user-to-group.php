<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname']) && isset($_POST['group_id']) && isset($_POST['user_id'])) {
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
        
        // Ambil ID grup dan ID pengguna dari formulir
        $groupId = $_POST['group_id'];
        $newUserId = $_POST['user_id'];

        // Periksa apakah pengguna yang sedang masuk adalah admin grup
        $queryAdminCheck = "SELECT is_admin FROM group_members WHERE group_id = :group_id AND user_id = :user_id";
        $stmtAdminCheck = $pdo->prepare($queryAdminCheck);
        $stmtAdminCheck->bindParam(':group_id', $groupId);
        $stmtAdminCheck->bindParam(':user_id', $userId);
        $stmtAdminCheck->execute();
        $isAdmin = $stmtAdminCheck->fetchColumn();

        if ($isAdmin) {
            // Periksa apakah pengguna sudah menjadi anggota grup
            $queryMemberCheck = "SELECT COUNT(*) FROM group_members WHERE group_id = :group_id AND user_id = :user_id";
            $stmtMemberCheck = $pdo->prepare($queryMemberCheck);
            $stmtMemberCheck->bindParam(':group_id', $groupId);
            $stmtMemberCheck->bindParam(':user_id', $newUserId);
            $stmtMemberCheck->execute();
            $isMember = $stmtMemberCheck->fetchColumn();

            if ($isMember) {
                // Jika pengguna sudah menjadi anggota grup, tampilkan pesan kesalahan
                echo "<h1>User already belongs to the group</h1>";
                echo "<p>The user you are trying to add is already a member of this group.</p>";
                exit;
            } else {
                // Tambahkan pengguna ke grup
                $queryAddUser = "INSERT INTO group_members (user_id, group_id, is_admin) VALUES (:new_user_id, :group_id, false)";
                $stmtAddUser = $pdo->prepare($queryAddUser);
                $stmtAddUser->bindParam(':new_user_id', $newUserId);
                $stmtAddUser->bindParam(':group_id', $groupId);
                $stmtAddUser->execute();

                // Alihkan kembali ke halaman detail grup setelah menambahkan pengguna
                header("Location: ../group-details.php?group_id=$groupId");
                exit;
            }
        } else {
            // Jika pengguna yang sedang masuk bukan admin grup, kembali ke halaman sebelumnya
            header("Location: ../groups.php");
            exit;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna tidak masuk atau data form tidak lengkap, arahkan kembali ke halaman login
    header("Location: ../index.php");
    exit;
}
?>
