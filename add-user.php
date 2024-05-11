<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    // Sertakan kode koneksi ke database di sini
    $host = 'localhost';
    $dbname = 'supermancyber';
    $username = 'root';
    $password = '';

    // Mendapatkan parameter group_id dari URL
    $groupId = $_GET['group_id'] ?? null;

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ambil informasi pengguna dari database
        $userId = $_SESSION['id'];
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($groupId) {
            // Pastikan grup yang dimaksud ada
            $queryGroup = "SELECT * FROM groups WHERE id = :group_id";
            $stmtGroup = $pdo->prepare($queryGroup);
            $stmtGroup->bindParam(':group_id', $groupId);
            $stmtGroup->execute();
            $group = $stmtGroup->fetch(PDO::FETCH_ASSOC);

            if (!$group) {
                echo "<h1>Group not found</h1>";
                echo "<p>The requested group was not found.</p>";
                exit;
            }

            // Periksa apakah pengguna adalah admin dari grup tersebut
            $queryCheckAdmin = "SELECT COUNT(*) AS count FROM group_members WHERE group_id = :group_id AND user_id = :user_id AND is_admin = 1";
            $stmtCheckAdmin = $pdo->prepare($queryCheckAdmin);
            $stmtCheckAdmin->bindParam(':group_id', $groupId);
            $stmtCheckAdmin->bindParam(':user_id', $userId);
            $stmtCheckAdmin->execute();
            $isAdmin = $stmtCheckAdmin->fetchColumn();

            // Jika pengguna bukan admin dari grup tersebut, kembalikan 403 Forbidden
            if (!$isAdmin) {
                http_response_code(403);
                echo "<h1>403 Forbidden</h1>";
                echo "<p>You are not authorized to add users to this group.</p>";
                exit;
            }
        } else {
            // Jika tidak ada group ID, tampilkan pesan error
            echo "<h1>Group ID is required</h1>";
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
        <title>Add User to Group</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="shadow w-450 p-3 text-center">
                <h3 class="display-4">Add User to <?php echo ($group['group_name']); ?></h3><br>
                <!-- Form untuk menambahkan pengguna ke grup -->
                <form action="php/add-user-to-group.php" method="post">
                    <input type="hidden" name="group_id" value="<?php echo $groupId; ?>">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User ID:</label>
                        <input type="number" name="user_id" class="form-control">
                    </div>
                    <br><button type="submit" class="btn btn-primary">Add User to Group</button><br><br>
                </form>
                <a href="groups.php" class="btn btn-secondary">Back</a>
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
