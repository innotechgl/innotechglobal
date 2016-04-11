<?PHP
require('includes/config.php');
require('includes/connect.php');
require('includes/Merchant.php');
$trans_id = $_POST['trans_id'];
$error_msg = $_POST['error'];
//$var = ($_POST['var']); //getting additional parameters
echo 'Tehnical error occurred! Please contact merchant! <br><br>' . $error_msg;
$sql = mysql_query("SELECT client_ip_addr FROM $db_table_transaction WHERE `trans_id` = '$trans_id'");
if (!$sql) {
    die('*** Invalid query1: ' . mysql_error());
} else {
    $row = mysql_fetch_row($sql);
    $client_ip_addr = $row[0];
}
$merchant = new Merchant($ecomm_server_url, $cert_url, $cert_pass, 1);
$resp = $merchant->getTransResult(urlencode($trans_id), $client_ip_addr);
$resp = htmlentities($resp, ENT_QUOTES);
$resp = $error_msg . ' + ' . $resp;
$sql = mysql_query("INSERT INTO $db_table_error VALUES ('', now(), 'ReturnFailURL', '$resp')
                                   ");
if (!$sql) {
    die('*** Invalid query2: ' . mysql_error());
}


?>