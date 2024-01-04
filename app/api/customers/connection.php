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
    $qResult = $con->query("SELECT c.* , p.name_pic, i.issue, u.name, MAX(a.date) AS date, MAX(a.follow_up) AS follow_up FROM client c
                            INNER JOIN pic p on c.id = p.id_client
                            INNER JOIN issues i on c.id_issues = i.id
                            INNER JOIN users u on c.id_sales = u.id
                            LEFT JOIN activities a on a.id_client = c.id
                            WHERE c.role = 'Customers' AND u.id = $user->id
                            GROUP BY id  
                            ORDER BY a.date DESC;");

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