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

        // Ambil informasi grup dari database
        $groupId = $_GET['group_id'] ?? null;
        if (!empty($groupId)) {
            // Query untuk memeriksa apakah pengguna adalah admin dari grup yang ingin diedit
            $userId = $_SESSION['id'];
            $queryCheckAdmin = "SELECT COUNT(*) AS count FROM group_members WHERE group_id = :group_id AND user_id = :user_id AND is_admin = 1";
            $stmtCheckAdmin = $pdo->prepare($queryCheckAdmin);
            $stmtCheckAdmin->bindParam(':group_id', $groupId);
            $stmtCheckAdmin->bindParam(':user_id', $userId);
            $stmtCheckAdmin->execute();
            $isAdmin = $stmtCheckAdmin->fetchColumn();

            if (!$isAdmin) {
                // Jika pengguna bukan admin dari grup yang ingin diedit, kirim kode status 403
                http_response_code(403);
                echo "<h1>403 Forbidden</h1>";
                echo "<p>You are not authorized to edit this group.</p>";
                exit;
            }

            // Ambil data grup
            $query = "SELECT * FROM groups WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $groupId);
            $stmt->execute();
            $groupData = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Jika ID grup tidak valid, redirect ke halaman sebelumnya atau halaman lain
            header("Location: groups.php");
            exit;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="shadow w-450 p-3 text-center">
            <h3 class="display-4">Edit Group</h3><br>
            <!-- Tampilkan formulir dengan data grup -->
            <form action="php/update-group.php" method="post">
                <!-- Input tersembunyi untuk menyimpan ID grup -->
                <input type="hidden" name="groupId" value="<?= htmlspecialchars($groupData['id'], ENT_QUOTES, 'UTF-8') ?>">

                <!-- Tampilkan data grup di dalam bidang formulir, misalnya $groupData['group_name'], dll. -->
                <label for="group_name">Group Name:</label>
                <input type="text" name="group_name" value="<?= htmlspecialchars($groupData['group_name'], ENT_QUOTES, 'UTF-8') ?>" required>

                <!-- Tambahkan bidang formulir lain untuk atribut grup lainnya -->

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
            <br><a href="groups.php" class="btn btn-secondary">Back</a>
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
