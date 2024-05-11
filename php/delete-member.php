<?php
session_start();

// Pastikan pengguna telah login
if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    // Sertakan kode koneksi ke database di sini
    $host = 'localhost';
    $dbname = 'supermancyber';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ambil ID pengguna yang akan dihapus dari URL
        $userId = $_GET['user_id'] ?? null;

        // Pastikan ID pengguna tidak kosong dan merupakan angka
        if (!empty($userId) && is_numeric($userId)) {
            // Lakukan penghapusan anggota grup dari tabel group_members
            $deleteMemberQuery = "DELETE FROM group_members WHERE user_id = :user_id";
            $deleteMemberStmt = $pdo->prepare($deleteMemberQuery);
            $deleteMemberStmt->bindParam(':user_id', $userId);
            $deleteMemberStmt->execute();

            // Periksa apakah grup tidak memiliki anggota lagi
            $checkGroupMembersQuery = "SELECT COUNT(*) AS member_count FROM group_members WHERE group_id = :group_id";
            $checkGroupMembersStmt = $pdo->prepare($checkGroupMembersQuery);
            $checkGroupMembersStmt->bindParam(':group_id', $_SESSION['group_id']);
            $checkGroupMembersStmt->execute();
            $memberCount = $checkGroupMembersStmt->fetch(PDO::FETCH_ASSOC)['member_count'];

            // Jika grup tidak memiliki anggota lagi, hapus grup
            if ($memberCount === 0) {
                $deleteGroupQuery = "DELETE FROM groups WHERE id = :group_id";
                $deleteGroupStmt = $pdo->prepare($deleteGroupQuery);
                $deleteGroupStmt->bindParam(':group_id', $_SESSION['group_id']);
                $deleteGroupStmt->execute();
            }

            // Redirect kembali ke halaman group-details.php setelah penghapusan berhasil
            header("Location: ../group-details.php?group_id={$_SESSION['group_id']}");
            exit();
        } else {
            // Jika parameter user_id tidak tersedia, redirect ke halaman group-details.php dengan pesan error
            header("Location: ../group-details.php?group_id={$_SESSION['group_id']}&error=user_id_not_provided");
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
