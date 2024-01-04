<?php
use Illuminate\Support\Facades\Auth;

header('Content-Type: application/json');
include 'db.php';
$con = new mysqli($host, $user, $pass, $databaseName);

// Periksa apakah pengguna terotentikasi
if (Auth::check()) {
    // Pengguna terotentikasi
    $user = Auth::user();

    // Query database for data
    $qResult = $con->query("SELECT q.*, c.company, u.name FROM quotation q 
                            LEFT JOIN pic p on p.id = q.id_pic
                            LEFT JOIN client c on c.id = p.id_client
                            INNER JOIN users u on u.id = q.id_sales
                            WHERE u.id = $user->id
                            GROUP BY id ORDER BY q.expired_date ASC;");

    $result = array();

    // Fetch result 
    while ($fetchData = $qResult->fetch_assoc()) {
        $result[] = $fetchData;
    }

    $arr = [
        "data" => $result,
    ];

    // Echo result as JSON 
    $hasil = json_encode($arr, JSON_PRETTY_PRINT);

    // Menampilkan hasil JSON
    echo $hasil;
} else {
    // Pengguna tidak terotentikasi
    echo json_encode(['error' => 'Pengguna tidak terotentikasi'], JSON_PRETTY_PRINT);
}
?>