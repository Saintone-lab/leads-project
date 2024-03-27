<?php
use Illuminate\Support\Facades\Auth;

header('Content-Type: application/json');
$host = "localhost";
$users = "root";
$pass = "";

$databaseName = "db_leads_v1";
$tableName = "product_out";

// Periksa apakah pengguna terotentikasi
if (Auth::check()) {
    // Pengguna terotentikasi
    $user = Auth::user();

    try {
        // Membuat koneksi PDO
        $pdo = new PDO("mysql:host=$host;dbname=$databaseName;charset=utf8", $users, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query database for data
        $query = "SELECT * FROM product_out";

        $stmt = $pdo->prepare($query);
        // $stmt->bindParam(':user_id', $user->id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch result 
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $arr = [
            "data" => $result,
        ];

        // Echo result as JSON 
        $hasil = json_encode($arr, JSON_PRETTY_PRINT);

        // Menampilkan hasil JSON
        echo $hasil;
    } catch (PDOException $e) {
        // Kesalahan koneksi atau eksekusi kueri
        echo json_encode(['error' => 'Kesalahan Database: ' . $e->getMessage()], JSON_PRETTY_PRINT);
    } finally {
        // Menutup koneksi PDO
        $pdo = null;
    }
} else {
    // Pengguna tidak terotentikasi
    echo json_encode(['error' => 'Pengguna tidak terotentikasi'], JSON_PRETTY_PRINT);
}
?>
