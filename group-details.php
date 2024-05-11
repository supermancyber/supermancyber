<?php
session_start();

// Periksa apakah pengguna telah masuk
if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    // Sertakan kode koneksi ke database di sini
    $host = 'localhost';
    $dbname = 'supermancyber';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ambil ID grup dari URL jika tersedia
        $groupId = $_GET['group_id'] ?? null;

        // Pastikan ID grup tidak kosong dan merupakan angka
        if (!empty($groupId) && is_numeric($groupId)) {
            // Query untuk memeriksa apakah pengguna terkait dengan grup yang diminta
            $queryCheckUserGroup = "SELECT COUNT(*) AS count, gm.is_admin FROM group_members gm WHERE group_id = :group_id AND user_id = :user_id";
            $stmtCheckUserGroup = $pdo->prepare($queryCheckUserGroup);
            $stmtCheckUserGroup->bindParam(':group_id', $groupId);
            $stmtCheckUserGroup->bindParam(':user_id', $_SESSION['id']);
            $stmtCheckUserGroup->execute();
            $userData = $stmtCheckUserGroup->fetch(PDO::FETCH_ASSOC);

            // Jika pengguna tidak terkait dengan grup yang diminta, tampilkan pesan 403
            if ($userData['count'] == 0) {
                http_response_code(403);
                echo "<h1>403 Forbidden</h1>";
                echo "<p>You are not authorized to access this group.</p>";
                exit;
            }

            // Simpan ID grup di session
            $_SESSION['group_id'] = $groupId;

            // Query untuk mendapatkan detail grup dan anggota grup dari database
            $query = "SELECT g.group_name, u.fname, gm.is_admin 
                      FROM groups g 
                      INNER JOIN group_members gm ON g.id = gm.group_id 
                      INNER JOIN users u ON gm.user_id = u.id
                      WHERE g.id = :group_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':group_id', $groupId);
            $stmt->execute();
            $group = $stmt->fetch(PDO::FETCH_ASSOC);

            // Periksa apakah grup ditemukan dalam database
            if (!$group) {
                // Jika grup tidak ditemukan, kirim header 404 dan tampilkan pesan
                http_response_code(404);
                echo "<h1>404 Not Found</h1>";
                echo "<p>The requested group was not found.</p>";
                exit;
            }

            // Query untuk mendapatkan daftar anggota grup beserta peran mereka
            $queryMembers = "SELECT u.fname, gm.is_admin, gm.user_id
                             FROM group_members gm 
                             INNER JOIN users u ON gm.user_id = u.id
                             WHERE gm.group_id = :group_id";
            $stmtMembers = $pdo->prepare($queryMembers);
            $stmtMembers->bindParam(':group_id', $groupId);
            $stmtMembers->execute();
            $members = $stmtMembers->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Jika ID grup tidak valid, redirect ke halaman sebelumnya atau halaman lain
            header("Location: index.php");
            exit;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna belum masuk, redirect ke halaman login
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Group Details</h2><br>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Group Name: <?php echo ($group['group_name']); ?></h3>
            <?php if ($userData['is_admin'] == 1): ?>
                <a href="add-user.php?group_id=<?php echo $groupId; ?>" class="btn btn-primary">Add User to Group</a>
            <?php endif; ?>
        </div><br>
        <h4>Group Members:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <?php if ($userData['is_admin'] == 1): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?php echo ($member['fname']); ?></td>
                        <td><?php echo $member['is_admin'] ? 'Admin' : 'Member'; ?></td>
                        <?php if ($userData['is_admin'] == 1): ?>
                            <td>
                                <a href="change-role.php?user_id=<?php echo $member['user_id']; ?>&group_id=<?php echo $groupId; ?>" class="btn btn-info">Change Role</a>
                                <a href="php/delete-member.php?user_id=<?php echo $member['user_id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="groups.php" class="btn btn-secondary">Back</a>
    </div>
</body>
</html>

