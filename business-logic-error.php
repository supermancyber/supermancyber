<?php
session_start();

// Cek apakah pengguna sudah login
if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    // Sertakan koneksi ke database di sini (jika perlu)
    $host = 'localhost';
    $dbname = 'supermancyber';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ambil informasi pengguna
        $userId = $_SESSION['id'];
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Inisialisasi session untuk shopping cart jika belum ada
        if (!isset($_SESSION['shopping_cart'])) {
            $_SESSION['shopping_cart'] = [];
        }

        // List item beserta harganya
        $items = [
            "Product A" => 10,
            "Product B" => 20
            // Tambahkan item lain di sini
        ];

        // Tambah item ke keranjang belanja jika tombol "Add to Cart" diklik
        if (isset($_POST["item_name"]) && isset($_POST["quantity"])) {
            $itemName = $_POST["item_name"];
            $quantity = $_POST["quantity"];

            // Validasi quantity agar tidak negatif
            // if ($quantity < 0) {
            //     echo "<p class='text-danger'>Error: Quantity cannot be negative.</p>";
            // }

            // Query untuk memeriksa apakah pengguna sudah memiliki produk tertentu di shopping cart-nya
            $queryCheckItem = "SELECT * FROM shopping_cart WHERE user_id = :user_id AND item_name = :item_name";
            $stmtCheckItem = $pdo->prepare($queryCheckItem);
            $stmtCheckItem->bindParam(':user_id', $userId);
            $stmtCheckItem->bindParam(':item_name', $itemName);
            $stmtCheckItem->execute();
            $existingItem = $stmtCheckItem->fetch(PDO::FETCH_ASSOC);

            if ($existingItem) {
                // Jika pengguna sudah memiliki produk tersebut, tambahkan jumlahnya
                $newQuantity = $existingItem['quantity'] + $quantity;
                $queryUpdateQuantity = "UPDATE shopping_cart SET quantity = :quantity WHERE user_id = :user_id AND item_name = :item_name";
                $stmtUpdateQuantity = $pdo->prepare($queryUpdateQuantity);
                $stmtUpdateQuantity->bindParam(':quantity', $newQuantity);
                $stmtUpdateQuantity->bindParam(':user_id', $userId);
                $stmtUpdateQuantity->bindParam(':item_name', $itemName);
                $stmtUpdateQuantity->execute();
            } else {
                // Jika pengguna belum memiliki produk tersebut, tambahkan produk ke shopping cart
                $queryInsert = "INSERT INTO shopping_cart (user_id, item_name, quantity, price) VALUES (:user_id, :item_name, :quantity, :price)";
                $stmtInsert = $pdo->prepare($queryInsert);
                $stmtInsert->bindParam(':user_id', $userId);
                $stmtInsert->bindParam(':item_name', $itemName);
                $stmtInsert->bindParam(':quantity', $quantity);
                $stmtInsert->bindParam(':price', $items[$itemName]);
                $stmtInsert->execute();
            }
        }

        // Proses form jika ada post request
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Hapus semua isi keranjang belanja jika tombol "Clear Shopping Cart" diklik
            if (isset($_POST['clear_cart'])) {
                $queryClear = "DELETE FROM shopping_cart WHERE user_id = :user_id";
                $stmtClear = $pdo->prepare($queryClear);
                $stmtClear->bindParam(':user_id', $userId);
                $stmtClear->execute();
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Logic Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h3 class="mb-4">Items</h3>
        <div class="row">
            <?php foreach ($items as $itemName => $price): ?>
            <div class="col">
                <h5><?php echo $itemName; ?></h5>
                <p>$<?php echo $price; ?></p>
                <form action="" method="post">
                    <input type="hidden" name="item_name" value="<?php echo $itemName; ?>">
                    <label for="quantity_<?php echo $itemName; ?>">Quantity:</label>
                    <input type="number" name="quantity" id="quantity_<?php echo $itemName; ?>" value="1" min="1">
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
        <br><br>

        <!-- Tampilkan Shopping Cart di sini -->
        <div class="row">
            <div class="col">
                <h2 class="my-4">Shopping Cart</h2>
            </div>
            <div class="col text-end">
                <!-- Tombol Clear Shopping Cart -->
                <form action="" method="post">
                    <br><button type="submit" class="btn btn-danger" name="clear_cart">Clear Shopping Cart</button>
                </form>
            </div>
        </div>

        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Item</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil item dari tabel shopping cart berdasarkan user ID
                    $queryCart = "SELECT * FROM shopping_cart WHERE user_id = :user_id";
                    $stmtCart = $pdo->prepare($queryCart);
                    $stmtCart->bindParam(':user_id', $userId);
                    $stmtCart->execute();
                    $totalPrice = 0;

                    // Tampilkan item dari shopping cart
                    while ($item = $stmtCart->fetch(PDO::FETCH_ASSOC)) {
                        $subtotal = $item['price'] * $item['quantity'];
                        $totalPrice += $subtotal;
                        echo "<tr>";
                        echo "<td>{$item['item_name']}</td>";
                        echo "<td>\${$item['price']}</td>";
                        echo "<td>{$item['quantity']}</td>";
                        echo "<td>\${$subtotal}</td>";
                        echo "</tr>";
                    }
                    ?>

                    <?php if ($totalPrice == 0 && empty($_SESSION['shopping_cart']) && array_sum(array_column($_SESSION['shopping_cart'], 'quantity')) == 0): ?>
                        <!-- <tr><td colspan='4'>Shopping Cart is empty.</td></tr> -->
                    <?php endif; ?>                    
                    
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end">Total</td>
                        <td>$<?php echo $totalPrice; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br><a href="labs.php" class="btn btn-secondary">Back</a>
    </div>
</body>
</html>
<?php
} else {
    // Jika pengguna belum login, redirect ke halaman login
    header("Location: index.php");
    exit;
}
?>
