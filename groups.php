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

        // Ambil informasi pengguna dari database
        $userId = $_SESSION['id'];
        $query = "SELECT gm.group_id, g.group_name FROM groups g JOIN group_members gm ON g.id = gm.group_id WHERE gm.user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Groups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <style>
        .action-buttons {
            display: flex;
            align-items: center;
        }

        .action-buttons .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="shadow p-3 text-center">
            <h3 class="display-4">Groups</h3>
            <br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Groups</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($groups as $group) { ?>
    <tr>
        <td><?= $group['group_name'] ?></td>
        <td class="action-buttons">
            <!-- Tombol untuk aksi lainnya -->
            <a class="btn btn-success" href="group-chat.php?group_id=<?php echo $group['group_id']; ?>">Chat</a>
            <a class="btn btn-primary" href="group-details.php?group_id=<?php echo $group['group_id']; ?>">Details</a>
            <?php
            // Periksa apakah pengguna adalah anggota dan admin dari grup yang sedang ditampilkan
            $groupId = $group['group_id'];
            $queryCheckAdmin = "SELECT COUNT(*) AS count FROM group_members WHERE group_id = :group_id AND user_id = :user_id AND is_admin = 1";
            $stmtCheckAdmin = $pdo->prepare($queryCheckAdmin);
            $stmtCheckAdmin->bindParam(':group_id', $groupId);
            $stmtCheckAdmin->bindParam(':user_id', $userId);
            $stmtCheckAdmin->execute();
            $isAdmin = $stmtCheckAdmin->fetchColumn();

            if ($isAdmin) { ?>
                <a class="btn btn-warning" href="edit-group.php?group_id=<?php echo $group['group_id']; ?>">Update</a>
                <!-- Form untuk penghapusan grup -->
                <form action="php/delete-group.php" method="post">
                    <input type="hidden" name="group_id" value="<?php echo $group['group_id']; ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            <?php } ?>
        </td>
    </tr>
<?php } ?>

                </tbody>
            </table>
            <br>
            <div class="d-flex justify-content-center">
                <a href="create-group.php" class="btn btn-primary btn-block mb-3">Create Group</a>
            </div>
            <div class="d-flex justify-content-center">
                <a href="labs.php" class="btn btn-secondary btn-block mb-3">Back</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
} else {
    header("Location: index.php");
    exit;
}
?>
