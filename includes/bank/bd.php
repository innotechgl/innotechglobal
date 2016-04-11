#!/usr/bin/php -q
<?PHP
require('includes/config.php');
require('includes/connect.php');
require("includes/Merchant.php");


$merchant = new Merchant($ecomm_server_url, $cert_url, $cert_pass, 1);

$resp = $merchant->closeDay();

//RESULT: OK RESULT_CODE: 500 FLD_075: 4 FLD_076: 6 FLD_087: 40 FLD_088: 60  

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

if (strstr($resp, 'FLD_075:')) {
    $count_reversal = explode('FLD_075: ', $resp);
    $count_reversal = preg_split('/\r\n|\r|\n/', $count_reversal[1]);
    $count_reversal = $count_reversal[0];
} else {
    $count_reversal = '';
}

if (strstr($resp, 'FLD_076:')) {
    $count_transaction = explode('FLD_076: ', $resp);
    $count_transaction = preg_split('/\r\n|\r|\n/', $count_transaction[1]);
    $count_transaction = $count_transaction[0];
} else {
    $count_transaction = '';
}

if (strstr($resp, 'FLD_087:')) {
    $amount_reversal = explode('FLD_087: ', $resp);
    $amount_reversal = preg_split('/\r\n|\r|\n/', $amount_reversal[1]);
    $amount_reversal = $amount_reversal[0];
} else {
    $amount_reversal = '';
}

if (strstr($resp, 'FLD_088:')) {
    $amount_transaction = explode('FLD_088: ', $resp);
    $amount_transaction = preg_split('/\r\n|\r|\n/', $amount_transaction[1]);
    $amount_transaction = $amount_transaction[0];
} else {
    $amount_transaction = '';
}


$sql = mysql_query("INSERT INTO $db_table_batch  VALUES ('', '$result', '$result_code', '$count_reversal', '$count_transaction', '$amount_reversal', '$amount_transaction', now(), '$resp')");


if (!$sql) {
    die('*** Invalid query: ' . mysql_error());
}


//echo $resp;

mysql_close($link);
?>
