<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    try {
        // Sertakan koneksi ke database di sini
        $host = 'localhost';
        $dbname = 'supermancyber';
        $username = 'root';
        $password = '';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query untuk mendapatkan data pengguna
        $userId = $_SESSION['id'];
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Tangkap kesalahan koneksi database
        echo "Error: " . $e->getMessage();
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Labs</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            .profile-icon {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background-color: #6c757d;
                color: #fff;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 18px;
            }
            .navbar-custom {
                background-color: #343a40;
            }
            .navbar-custom .navbar-brand, .navbar-custom .nav-link {
                color: #ffffff;
            }
            .card-custom {
                margin-bottom: 30px;
            }
            .card-custom .card-body {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 150px;
            }
            .center {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
            }
            .menu-icon {
                font-size: 3rem;
            }
            .card-link {
                text-decoration: none;
                color: inherit;
                display: flex;
                align-items: center;
                height: 100%;
                padding: 20px;
            }
            .card-link:hover {
                text-decoration: none;
                color: inherit;
                background-color: #f8f9fa;
            }
            .icon-box {
                width: 70px;
                height: 70px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 10px;
                margin-right: 20px;
            }
            .icon-box-1 { background-color: #ffadad; }
            .icon-box-2 { background-color: #ffd6a5; }
            .icon-box-3 { background-color: #fdffb6; }
            .icon-box-4 { background-color: #caffbf; }
            .icon-box-5 { background-color: #9bf6ff; }
            .icon-box-6 { background-color: #a0c4ff; }
            .icon-box-7 { background-color: #bdb2ff; }
            .icon-box-8 { background-color: #ffc6ff; }
            .icon-box-9 { background-color: #ffb3b3; }
            .icon-box-10 { background-color: #f0f0f0; }
            .nav-text {
                font-size: 1rem;
                line-height: 1.2;
            }
            .card-text {
                font-size: 1.15rem;
            }
        </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    </head>
    <body>
        <!-- Top Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid">
                <a class="navbar-brand mx-auto" href="#">Labs</a>
                <div class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="help.php">
                                <i class="bi bi-question-circle" style="font-size: 1.5rem;"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="bi bi-box-arrow-right" style="font-size: 1.5rem;"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <div class="profile-icon">
                                    <?php echo strtoupper(substr($_SESSION['fname'], 0, 1)); ?>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link nav-text">
                                Hello, <?php echo($_SESSION['fname']); ?>
                                <br>
                                <span class="text-end d-block">ID: <?php echo($_SESSION['id']); ?></span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container my-5">
            <div class="row justify-content-center">
                <?php
                $menuItems = [
                    ["theory/csrf.php", "CSRF - Edit Profile", "bi bi-shield-lock", "icon-box-1"],
                    ["theory/idor.php", "IDOR - Edit Profile", "bi bi-key", "icon-box-2"],
                    ["theory/xss.php", "Reflected XSS", "bi bi-file-code", "icon-box-3"],
                    ["theory/bac.php", "Broken Access Control", "bi bi-unlock", "icon-box-4"],
                    ["theory/command-execution.php", "Command Execution", "bi bi-terminal", "icon-box-5"],
                    ["theory/lfi.php", "Local File Inclusion", "bi bi-file-earmark", "icon-box-6"],
                    ["theory/insecure-file-upload.php", "Insecure File Upload", "bi bi-cloud-arrow-up", "icon-box-7"],
                    ["theory/ssrf.php", "Server Side Request Forgery", "bi bi-globe", "icon-box-8"],
                    ["theory/business-logic-error.php", "Business Logic Error", "bi bi-gear", "icon-box-9"],
                    ["theory/setup-burp-suite.php", "Setup Burp Suite", "bi bi-tools", "icon-box-10"],
                ];

                foreach ($menuItems as $index => $item) {
                    $iconBoxClass = isset($item[3]) ? $item[3] : '';
                    echo '<div class="col-md-4 card-custom">
                            <div class="card">
                                <a href="'.$item[0].'" class="card-link">
                                    <div class="icon-box '.$iconBoxClass.'">
                                        <i class="'.$item[2].' menu-icon"></i>
                                    </div>
                                    <div class="card-text">'.$item[1].'</div>
                                </a>
                            </div>
                          </div>';
                }
                ?>
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
