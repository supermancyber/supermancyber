<?php
session_start();

// Pastikan pengguna sudah login
if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    // Sertakan kode koneksi ke database
    $host = 'localhost';
    $dbname = 'supermancyber';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Pastikan ID grup yang akan dihapus ada dalam data POST
        if(isset($_POST['group_id'])) {
            $groupId = $_POST['group_id'];

            // Lakukan penghapusan anggota grup dari tabel group_members
            $deleteMembersQuery = "DELETE FROM group_members WHERE group_id = :group_id";
            $deleteMembersStmt = $pdo->prepare($deleteMembersQuery);
            $deleteMembersStmt->bindParam(':group_id', $groupId);
            $deleteMembersStmt->execute();

            // Lakukan penghapusan grup dari tabel groups
            $deleteGroupQuery = "DELETE FROM groups WHERE id = :group_id";
            $deleteGroupStmt = $pdo->prepare($deleteGroupQuery);
            $deleteGroupStmt->bindParam(':group_id', $groupId);
            $deleteGroupStmt->execute();

            // Redirect ke halaman groups.php setelah penghapusan berhasil
            header("Location: ../groups.php");
            exit();
        } else {
            // Jika parameter group_id tidak tersedia, redirect ke halaman groups.php dengan pesan error
            header("Location: ../groups.php?error=groupid_not_provided");
            exit();
        }
    } catch (PDOException $e) {
        // Tangani error database
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna belum login, redirect ke halaman login.php
    header("Location: ../index.php");
    exit;
}
?>
