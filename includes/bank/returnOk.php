<?PHP
require('includes/config.php');
require('includes/connect.php');
require('includes/Merchant.php');
$trans_id = $_POST['trans_id'];
//$var = ($_POST['var']); //getting additional parameters
print_r($_POST);
$sql = mysql_query("SELECT client_ip_addr FROM $db_table_transaction WHERE `trans_id` = '$trans_id'");
if (!$sql) {
    die('*** Invalid query: ' . mysql_error());
} else {
    $row = mysql_fetch_row($sql);
    $client_ip_addr = $row[0];
}
$merchant = new Merchant($ecomm_server_url, $cert_url, $cert_pass, 1);
$resp = $merchant->getTransResult(urlencode($trans_id), $client_ip_addr);
if (strstr($resp, 'RESULT:')) {
    //$resp example RESULT: OK RESULT_CODE: 000 3DSECURE: NOTPARTICIPATED RRN: 915300393049 APPROVAL_CODE: 705368 CARD_NUMBER: 4***********9913
    if (strstr($resp, 'RESULT:')) {
        $result = explode('RESULT: ', $resp);
        $result = preg_split('/\r\n|\r|\n/', $result[1]);
        $result = $result[0];
    } else {
        $result = '';
    }
    if (strstr($resp, 'RESULT_CODE:')) {
        $result_code = explode('RESULT_CODE: ', $resp);
        $result_code = preg_split('/\r\n|\r|\n/', $result_code[1]);
        $result_code = $result_code[0];
    } else {
        $result_code = '';
    }
    if (strstr($resp, '3DSECURE:')) {
        $result_3dsecure = explode('3DSECURE: ', $resp);
        $result_3dsecure = preg_split('/\r\n|\r|\n/', $result_3dsecure[1]);
        $result_3dsecure = $result_3dsecure[0];
    } else {
        $result_3dsecure = '';
    }
    if (strstr($resp, 'CARD_NUMBER:')) {
        $card_number = explode('CARD_NUMBER: ', $resp);
        $card_number = preg_split('/\r\n|\r|\n/', $card_number[1]);
        $card_number = $card_number[0];
    } else {
        $card_number = '';
    }
    $sql = mysql_query("UPDATE $db_table_transaction SET
      `result` = '$result',
      `result_code` = '$result_code',
      `result_3dsecure` = '$result_3dsecure',
      `card_number` = '$card_number',
      `response` = '$resp'      
      WHERE `trans_id` = '$trans_id'");
    if (!$sql) {
        die('*** Invalid query: ' . mysql_error());
    }
    echo $resp;
} else {
    echo $resp;
    $resp = htmlentities($resp, ENT_QUOTES);
    $sql = mysql_query("INSERT INTO $db_table_error VALUES ('', now(), 'ReturnOkURL', '$resp')
                        ");
    if (!$sql) {
        die('*** Invalid query2: ' . mysql_error());
    }
}
var_dump($result_code);
?>