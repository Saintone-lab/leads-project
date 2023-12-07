<?php
header('Content-Type: application/json');
include 'db.php';
$con = new mysqli($host, $user, $pass, $databaseName);


$qResult = $con->query("SELECT q.*, c.company, u.name, d.detail_product FROM quotation q 
LEFT JOIN client c on c.id = q.id_client
INNER JOIN users u on u.id = q.id_sales
LEFT JOIN detail_quotation d on d.id_quotation = q.id
GROUP BY id ORDER BY q.folup_date ASC;");        
$result = array();                       
while ($fetchData = $qResult->fetch_assoc()) {
    $result[] = $fetchData;
}

$arr = [
    "data" => $result,
];
$hasil = json_encode($arr, JSON_PRETTY_PRINT);

// Menampilkan hasil JSON
echo $hasil;

?>