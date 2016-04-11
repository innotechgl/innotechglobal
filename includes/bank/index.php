<?php
require('includes/config.php');
require('includes/connect.php');
require("includes/Merchant.php");
if ((isset($_GET['action']))) {
    $i = $_GET['action'];
} else {
    $i = 'form_sms';
}
switch ($i) {
//CASE FORM DMS
    case 'form_dms':
        echo ' DMS | <a href="?action=form_sms">SMS</a> | <a href="?action=select">Transaction list</a> | <a href="?action=batch">Batch list</a> | <a href="?action=error">Error list</a>';
        print ('
<br>
<br>
<form action="?action=startdmsauth" method="post">
<table>
        <tr>
            <th align="right">Amount: </th>
            <td align="left"><input type="text" name="post_amount" value="10" /></td>
        </tr>
        <tr>
            <th align="right">Currency: </th>
            <td align="left"><input type="text" name="post_currency" value="978" /></td>
        </tr>
        <tr>
            <th align="right">Client_ip_addr: </th>
            <td align="left"><input type="text" name="post_ip" value="' . $_SERVER["REMOTE_ADDR"] . '" /></td>
        </tr>
        <tr>
            <th align="right">Description: </th>
            <td align="left"><input type="text" name="post_description" value="Order number: 123" /></td>
        </tr>
        <tr>
            <th align="right">Language: </th>
            <td align="left"><input type="text" name="post_language" value="lv" /></td>
        </tr>
        <tr>
            <td></td>
            <td align="right"><input type="Reset"> <input type="submit" value="Submit"></td>
        </tr>
    </table>

</form>');
        break;
//CASE FORM SMS
    case 'form_sms':
        echo '<a href="?action=form_dms">DMS</a> | SMS | <a href="?action=select">Transaction list</a> | <a href="?action=batch">Batch list</a> | <a href="?action=error">Error list</a>';
        print ('
<br>
<br>
<form action="?action=startsmstrans" method="post">
<table>
        <tr>
            <th align="right">Amount: </th>
            <td align="left"><input type="text" name="post_amount" value="10" /></td>
        </tr>
        <tr>
            <th align="right">Currency: </th>
            <td align="left"><input type="text" name="post_currency" value="978" /></td>
        </tr>
        <tr>
            <th align="right">Client_ip_addr: </th>
            <td align="left"><input type="text" name="post_ip" value="' . $_SERVER["REMOTE_ADDR"] . '" /></td>
        </tr>
        <tr>
            <th align="right">Description: </th>
            <td align="left"><input type="text" name="post_description" value="Order number: 123" /></td>
        </tr>
        <tr>
            <th align="right">Language: </th>
            <td align="left"><input type="text" name="post_language" value="lv" /></td>
        </tr>
        <tr>
            <td></td>
            <td align="right"><input type="Reset"> <input type="submit" value="Submit"></td>
        </tr>
    </table>

</form>');
        break;
//CASE STARTDMSAUTH
    case 'startdmsauth':
        $amount = $_POST['post_amount'];
        $currency = $_POST['post_currency'];
        $ip = $_POST['post_ip'];
        $description = urlencode(htmlspecialchars($_POST['post_description'], ENT_QUOTES));
        $language = $_POST['post_language'];
        $merchant = new Merchant($ecomm_server_url, $cert_url, $cert_pass, 1);
        $resp = $merchant->startDMSAuth($amount, $currency, $ip, $description, $language);
        if (substr($resp, 0, 14) == "TRANSACTION_ID") {
            $trans_id = substr($resp, 16, 28);
            $url = $ecomm_client_url . "?trans_id=" . urlencode($trans_id);
            header("Location: $url");
            $sql = mysql_query("INSERT INTO $db_table_transaction VALUES ('', '$trans_id', '$amount', '$currency', '$ip', '$description', '$language', 'NO', '???', '???', '???', '???', now(), '$resp', '', '')
                        ");
            if (!$sql) {
                die('*** Invalid query1: ' . mysql_error());
            }
        } else {
            echo $resp;
            $resp = htmlentities($resp, ENT_QUOTES);
            $sql = mysql_query("INSERT INTO $db_table_error VALUES ('', now(), 'startDMSAuth', '$resp')
                        ");
            if (!$sql) {
                die('*** Invalid query2: ' . mysql_error());
            }
        }
        mysql_close($link);
        break;
//CASE STARTSMSTRANS
    case 'startsmstrans':
        $amount = $_POST['post_amount'];
        $currency = $_POST['post_currency'];
        $ip = $_POST['post_ip'];
        $description = urlencode(htmlspecialchars($_POST['post_description'], ENT_QUOTES));
        $language = $_POST['post_language'];
        $merchant = new Merchant($ecomm_server_url, $cert_url, $cert_pass, 1);
        $resp = $merchant->startSMSTrans($amount, $currency, $ip, $description, $language);
        if (substr($resp, 0, 14) == "TRANSACTION_ID") {
            $trans_id = substr($resp, 16, 28);
            $url = $ecomm_client_url . "?trans_id=" . urlencode($trans_id);
            $sql = mysql_query("INSERT INTO $db_table_transaction VALUES ('', '$trans_id', '$amount', '$currency', '$ip', '$description', '$language', '---', '???', '???', '???', '???', now(), '$resp', '', '')");
            header("Location: $url");
            if (!$sql) {
                die('*** Invalid query: ' . mysql_error());
            }
        } else {
            echo $resp;
            $resp = htmlentities($resp, ENT_QUOTES);
            $sql = mysql_query("INSERT INTO $db_table_error VALUES ('', now(), 'startsmstrans', '$resp')
                        ");
            if (!$sql) {
                die('*** Invalid query2: ' . mysql_error());
            }
        }
        mysql_close($link);
        break;
//CASE SELECT
    case 'select':
        echo '<html>
<head>
<style type="text/css">
* {
font-size: 13px;
}

a {
font-size: 16px;
font-weight: normal;
}

span {
font-size: 16px;
font-weight: normal;
}

tr span{
font-size: 13px;
}

b{
font-size: 16px;
font-weight: normal;
}

</style>
</head>
<body>
<b><a href="?action=form_dms">DMS</a> | <a href="?action=form_sms">SMS</a> | Transaction list | <a href="?action=batch">Batch list</a> | <a href="?action=error">Error list</a></b>';
        $sql = mysql_query("SELECT * FROM $db_table_transaction ORDER by `id` ASC");
        if (!$sql) {
            die('Invalid query: ' . mysql_error());
        }
        echo "
      <table border=0 >
      <tr bgcolor=\"#c8c8c8\">  
            <td>id</td>
            <td>date</td>
            <td>trans_id</td>
            <td>amount</td>
            <td>currency</td>
            <td>ip</td>
            <td>description</td>
            <td>language</td>
            <td>RESULT</td>
            <td>RESULT CODE</td>
            <td>3D Secure</td>
            <td>Card nr.</td>
            <td>DMS</td>
            <td>Reverse</td>
            <td>Update result</td>
      </tr>";
        while ($row = mysql_fetch_row($sql)) {
            if ($row[9] == '000') {
                $color = "bgcolor=\"#b0f0b0\"";
            } elseif ($row[9] == '400' OR $row[8] == 'AUTOREVERSED' OR $row[8] == 'REVERSED') {
                $color = "bgcolor=\"#B2D1F0\"";
            } elseif ($row[9] == '???' OR $row[8] == 'CREATED' OR $row[8] == 'TIMEOUT' OR $row[8] == 'PENDING') {
                $color = "bgcolor=\"#f3f3f3\"";
            } else {
                $color = "bgcolor=\"#ffbaba\"";
            }
            if ($row[9] == '400') {
                $color2 = "bgcolor=\"#B2D1F0\"";
            } else {
                $color2 = "";
            }
            if ($row[7] == 'NO' AND $row[9] == '000') {
                $makedmstrans = "            <td><a href='?action=confirm_dms&id=" . $row[0] . "'>makeDMStrans</a></td>";
            } elseif ($row[7] == 'YES') {
                $makedmstrans = '            <td style="color: gray;">DMS done; amount=' . $row[15] . '</td>';
            } else {
                $makedmstrans = '            <td>---</td>';
            }
            if ($row[9] == '000' AND $row[8] == 'OK') {
                $reverse = " <td><a href='?action=confirm_reverse&id=" . $row[0] . "'>REVERSE</a></td>";
            } elseif ($row[8] == 'REVERSED' AND $row[14] == '0') {
                $reverse = '  <td style="color: gray;">Autoreversal</td>';
            } elseif ($row[9] == '400' OR $row[8] == 'REVERSED') {
                $reverse = '  <td style="color: gray;">Reversed; amount=' . $row[14] . '</td>';
            } else {
                $reverse = '  <td style="color: gray;">---</td>';
            }
            $update = " <td><a href='?action=update&id=" . $row[0] . "'>Update</a></td>";
            printf("
      <tr bgcolor=\"#f3f3f3\">  
            <td>%s</td>
            <td>%s</td>
            <td><span title=\"%s\">%s</span></td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td %s><span title=\"%s\">%s</span></td>
            <td %s><span title=\"%s\">%s</span></td>
            <td><span title=\"%s\">%s</span></td>
            <td><span title=\"%s\">%s</span></td>
  
        
             %s 
             %s
             %s
      </tr>                                
                   ",
                $row[0], $row[12], $row[13], $row[1], $row[2], $row[3], $row[4], urldecode($row[5]), $row[6], $color, $row[13], $row[8], $color2, $row[13], $row[9], $row[13], $row[10], $row[13], $row[11], $makedmstrans, $reverse, $update);
        }
        echo "</table>
      </body></html>";
        mysql_close($link);
        break;
//CASE CLOSE BUSINESS DAY
    case 'close':
        $merchant = new Merchant($ecomm_server_url, $cert_url, $cert_pass, 1);
        $resp = $merchant->closeDay();
        if (strstr($resp, 'RESULT:')) {
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
            echo $resp;
        } else {
            echo $resp;
            $resp = htmlentities($resp, ENT_QUOTES);
            $sql = mysql_query("INSERT INTO $db_table_error VALUES ('', now(), 'closeDay', '$resp')
                        ");
            if (!$sql) {
                die('*** Invalid query2: ' . mysql_error());
            }
        }
        '<br><br><a href="?action=select">Back to Transaction list</a><br>';
        mysql_close($link);
        break;
//CASE ERROR LIST
    case 'error':
        echo '<a href="?action=form_dms">DMS</a> | <a href="?action=form_sms">SMS</a> | <a href="?action=select">Transaction list</a> | <a href="?action=batch">Batch list</a> | Error list';
        $sql = mysql_query("SELECT * FROM $db_table_error ORDER by `id` ASC");
        if (!$sql) {
            die('Invalid query: ' . mysql_error());
        }
        echo "
      <table>
      <tr bgcolor=\"#c8c8c8\">  
            <td>id</td>
            <td>Error time</td>
            <td>Action</td>
            <td>Response</td>
      </tr>";
        while ($row = mysql_fetch_row($sql)) {
            printf("
      <tr bgcolor=\"#f3f3f3\">  
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>

      </tr>", $row[0], $row[1], $row[2], $row[3]);
        }
        echo "</table>";
        mysql_close($link);
        break;
//CASE Batch history
    case 'batch':
        echo '<a href="?action=form_dms">DMS</a> | <a href="?action=form_sms">SMS</a> | <a href="?action=select">Transaction list</a> | Batch list | <a href="?action=error">Error list</a>';
        $sql = mysql_query("SELECT * FROM $db_table_batch ORDER by `id` ASC");
        if (!$sql) {
            die('Invalid query: ' . mysql_error());
        }
        echo "
      <table>
      <tr bgcolor=\"#c8c8c8\">  
            <td>id</td>
            <td>close date</td>
            <td>RESULT</td>
            <td>RESULT CODE</td>
            <td>count_reversal</td>
            <td>count_transaction</td>
            <td>amount_reversal</td>
            <td>amount_transaction</td>
      </tr>";
        while ($row = mysql_fetch_row($sql)) {
            printf("
      <tr bgcolor=\"#f3f3f3\">  
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>

      </tr>", $row[0], $row[7], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);
        }
        echo "</table>
   <br><br><br>To close business day, press this -->  <a href='?action=close'>Close BD</a><br>";
        mysql_close($link);
        break;
//CASE CONFIRM DMS transaction
    case 'confirm_dms':
        $id = $_GET['id']; //for getting MySQL data
        $sql = mysql_query("SELECT * FROM $db_table_transaction WHERE `id`='$id'");
        if (!$sql) {
            die('*** Invalid query: ' . mysql_error());
        }
        $row = mysql_fetch_row($sql);
        $amount = $row[2];
        echo '<form action="?action=makedmstrans&id=' . $id . '" method="POST">
      DMS transaction amount: <input type="text" name="amount" value="' . $amount . '" /><br>
      <input type="submit" Value="Make DMS" />
      </form>';
        mysql_close($link);
        break;
//CASE MAKE DMS TRANS
    case 'makedmstrans':
        $id = $_GET['id']; //for getting MySQL data
        $merchant = new Merchant($ecomm_server_url, $cert_url, $cert_pass, 1);
        $result = mysql_query("SELECT * FROM $db_table_transaction WHERE `id`='$id'");
        if (!$result) {
            die('*** Invalid query: ' . mysql_error());
        }
        $row = mysql_fetch_row($result);
        $auth_id = urlencode($row[1]);
        $amount = $_POST['amount'];
        $currency = urlencode($row[3]);
        $ip = urlencode($row[4]);
        $desc = urlencode($row[5]);
        $language = urlencode($row[6]);
        $resp = $merchant->makeDMSTrans($auth_id, $amount, $currency, $ip);
        if (substr($resp, 8, 2) == "OK") {
            $trans_id = $row[1];
            $result = mysql_query("UPDATE $db_table_transaction SET `dms_ok` = 'YES', makeDMS_amount = '$amount' WHERE `trans_id` = '$trans_id'");
            if (!$result) {
                die('*** Invalid query: ' . mysql_error());
            }
            echo $resp;
        } else {
            echo $resp;
            $resp = htmlentities($resp, ENT_QUOTES);
            $sql = mysql_query("INSERT INTO $db_table_error VALUES ('', now(), 'makeDMSTrans', '$resp')
                        ");
            if (!$sql) {
                die('*** Invalid query2: ' . mysql_error());
            }
        }
        echo '<br><br> <a href="?action=select">Back</a>';
        mysql_close($link);
        break;
//CASE CONFIRM_REVERSE
    case 'confirm_reverse':
        $id = $_GET['id']; //for getting MySQL data
        $sql = mysql_query("SELECT * FROM $db_table_transaction WHERE `id`='$id'");
        if (!$sql) {
            die('*** Invalid query: ' . mysql_error());
        }
        $row = mysql_fetch_row($sql);
        $trans_id = urlencode($row[1]);
        $amount = $row[2];
        echo '<form action="?action=reverse&id=' . $id . '" method="POST">
      Reverse amount: <input type="text" name="amount" value="' . $amount . '" /><br>
      <input type="submit" Value="Reverse" />
      </form>';
        mysql_close($link);
        break;
//CASE REVERSE
    case 'reverse':
        $id = $_GET['id']; //for getting MySQL data
        $sql = mysql_query("SELECT * FROM $db_table_transaction WHERE `id`='$id'");
        if (!$sql) {
            die('*** Invalid query: ' . mysql_error());
        }
        $row = mysql_fetch_row($sql);
        $trans_id = urlencode($row[1]);
        $amount = $_POST['amount'];
        if ($amount == '0') {
            die("Amount invalid: $amount <br><br><a href=\"javascript:history.go(-1)\"><< Back </a>");
        }
        $merchant = new Merchant($ecomm_server_url, $cert_url, $cert_pass, 1);
        $resp = $merchant->reverse($trans_id, $amount);
        if (substr($resp, 8, 2) == "OK" OR substr($resp, 8, 8) == "REVERSED") {
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
            $trans_id = $row[1];
            $sql = mysql_query("UPDATE $db_table_transaction SET `reversal_amount` = '$amount', `result_code` = '$result_code', `result` = '$result', `response` = '$resp' WHERE `trans_id` = '$trans_id'");
            if (!$sql) {
                die('*** Invalid query: ' . mysql_error());
            } else {
                echo $resp;
            }
        } else {
            echo $resp;
            $resp = htmlentities($resp, ENT_QUOTES);
            $sql = mysql_query("INSERT INTO $db_table_error VALUES ('', now(), 'reverse', '$resp')");
            if (!$sql) {
                die('*** Invalid query222: ' . mysql_error());
            }
        }
        echo '<br><br><a href="?action=select">Back to Transaction list</a><br>';
        mysql_close($link);
        break;
//CASE UPDATE
    case 'update':
        $id = $_GET['id']; //for getting MySQL data
        $sql = mysql_query("SELECT * FROM $db_table_transaction WHERE `id`='$id'");
        if (!$sql) {
            die('*** Invalid query: ' . mysql_error());
        }
        $row = mysql_fetch_row($sql);
        $trans_id = $row[1];
        $client_ip_addr = $row[4];
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
            echo $resp;
        } else {
            echo $resp;
            $resp = htmlentities($resp, ENT_QUOTES);
            $sql = mysql_query("INSERT INTO $db_table_error VALUES ('', now(), 'update', '$resp')
                        ");
            if (!$sql) {
                die('*** Invalid query2: ' . mysql_error());
            }
        }
        echo '<br><br><a href="?action=select">Back to Transaction list</a><br>';
        mysql_close($link);
        break;
//CASE DEFAULT
    default:
        echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=?action=form_sms">';
        break;
}
?>