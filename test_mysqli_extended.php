<?php
/**
 * User: onurcanalp
 * Date: 14/01/15
 * Time: 11:43
 */

include("mysqli_extended.php");

try {

    $db = new Database('localhost', 'root', 'root');
    $db->select_db('onurtest');
    $rows = $db->query("SELECT * FROM testtable");
    while ($row = $rows->fetch_assoc()){
        echo "Id: " . $row['id'] . "\n";
    }
} catch (DBConnectException $e){
    echo "Bağlantı Hatası: " . $e->getMessage() . " (" . $e->getCode() . ")\n";
} catch (DBQueryException $e){
    echo "Sorgu Hatası: " . $e->getMessage() . " (" . $e->getCode() . ")\n";
}