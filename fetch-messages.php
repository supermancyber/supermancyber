<?php
session_start();

// Pastikan pengguna telah masuk dan ada sesi grup yang ditetapkan
if (isset($_SESSION['id']) && isset($_SESSION['group_id'])) {
    $host = 'localhost';
    $dbname = 'supermancyber';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ambil ID grup dari sesi
        $groupId = $_SESSION['group_id'];

        // Query untuk mengambil pesan dari database
        $query = "SELECT cm.*, u.fname 
                  FROM chat_messages cm 
                  INNER JOIN users u ON cm.user_id = u.id 
                  WHERE cm.group_id = :group_id 
                  ORDER BY cm.timestamp ASC"; // Ubah 'created_at' menjadi 'timestamp'
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':group_id', $groupId);
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua pesan

        // Kirim pesan dalam format JSON
        header('Content-Type: application/json');
        echo json_encode($messages);

    } catch (PDOException $e) {
        // Tangani kesalahan database
        echo json_encode(array('error' => 'Database error: ' . $e->getMessage()));
    }
} else {
    // Jika pengguna tidak masuk atau sesi grup tidak diatur, kirim balasan kosong
    echo json_encode(array());
}
?>
