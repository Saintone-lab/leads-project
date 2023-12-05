<?php
header('Content-Type: application/json');
include 'db.php';
$con = new mysqli($host, $user, $pass, $databaseName);

//--------------------------------------------------------------------------
// 2) Query database for data
//--------------------------------------------------------------------------
$qResult = $con->query("SELECT v.* , c.company FROM visit v INNER JOIN client c on v.id_client = c.id");          //query
$result = array();                        //fetch result 

while ($fetchData = $qResult->fetch_assoc()) {
    $result[] = $fetchData;
}

$arr = [
    "data" => $result,
];

//--------------------------------------------------------------------------
// 3) echo result as json 
//--------------------------------------------------------------------------
$hasil = json_encode($arr, JSON_PRETTY_PRINT);

// Menampilkan hasil JSON
echo $hasil;

?>