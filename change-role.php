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

        // Ambil informasi pengguna yang akan diubah rolenya
        $userId = $_GET['user_id'] ?? null;
        $groupId = $_GET['group_id'] ?? null;

        if (!empty($userId) && is_numeric($userId) && !empty($groupId)) {
            // Query untuk mendapatkan informasi pengguna
            $query = "SELECT * FROM users WHERE id = :user_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$userData) {
                // Jika pengguna tidak ditemukan, tampilkan pesan error
                echo "<h1>User not found</h1>";
                echo "<p>The requested user was not found.</p>";
                exit;
            }

            // Periksa apakah pengguna adalah admin dari grup yang dimaksud
            $queryCheckAdmin = "SELECT is_admin FROM group_members WHERE user_id = :user_id AND group_id = :group_id AND is_admin = 1";
            $stmtCheckAdmin = $pdo->prepare($queryCheckAdmin);
            $stmtCheckAdmin->bindParam(':user_id', $_SESSION['id']);
            $stmtCheckAdmin->bindParam(':group_id', $groupId);
            $stmtCheckAdmin->execute();
            $isAdmin = $stmtCheckAdmin->fetchColumn();

            if (!$isAdmin) {
                // Jika pengguna bukan admin dari grup tersebut, kembalikan status 403 Forbidden
                http_response_code(403);
                echo "<h1>403 Forbidden</h1>";
                echo "<p>You are not authorized to change the role of this user.</p>";
                exit;
            }
        } else {
            // Jika data pengguna tidak valid, kembali ke halaman sebelumnya
            header("Location: group-details.php?group_id=$groupId");
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
        <title>Change User Role</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="shadow w-450 p-3 text-center">
                <h3 style="font-size: 40px;" class="display-4">Change User Role</h3>
                <br><p>Changing role for: <?php echo $userData['fname']; ?></p><br>
                <form action="php/update-role.php" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                    <input type="hidden" name="group_id" value="<?php echo $groupId; ?>">
                    <div class="mb-3">
                        <label for="new_role" class="form-label">New Role:</label>
                        <select name="is_admin" class="form-select">
                            <?php
                                // Query untuk mendapatkan peran pengguna dalam grup
                                $queryRole = "SELECT is_admin FROM group_members WHERE user_id = :user_id AND group_id = :group_id";
                                $stmtRole = $pdo->prepare($queryRole);
                                $stmtRole->bindParam(':user_id', $userId);
                                $stmtRole->bindParam(':group_id', $groupId);
                                $stmtRole->execute();
                                $userRole = $stmtRole->fetchColumn();

                                // Menampilkan opsi berdasarkan peran pengguna
                                if ($userRole == 1) {
                                    echo '<option value="1" selected>Admin</option>';
                                    echo '<option value="0">Member</option>';
                                } else {
                                    echo '<option value="1">Admin</option>';
                                    echo '<option value="0" selected>Member</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <br><button type="submit" class="btn btn-primary">Change Role</button>
                </form>
                <br><a href="group-details.php?group_id=<?php echo $groupId; ?>" class="btn btn-secondary">Back</a>
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
