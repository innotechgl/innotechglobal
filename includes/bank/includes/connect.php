<?PHP

//connect to MySQL server
$link = mysql_connect($db_host, $db_user, $db_pass, true);
if (!$link) {
    die(' ***Could not connect MySQL server: ' . mysql_error());
}
mysql_query("CREATE DATABASE /*!32312 IF NOT EXISTS*/ $db_database");
// connect to DB
$db_selected = mysql_select_db($db_database, $link);
if (!$db_selected) {
    die (' ***Could not connect MySQL DB: ' . mysql_error());
}
mysql_query("CREATE TABLE /*!32312 IF NOT EXISTS*/ $db_table_transaction  (
      id INT(10) NOT NULL AUTO_INCREMENT,
      trans_id VARCHAR(50),
      amount INT(10),
      currency INT(10),
      client_ip_addr VARCHAR(50),
      description TEXT,
      language VARCHAR(50),
      dms_ok VARCHAR(50),
      result VARCHAR(50),
      result_code VARCHAR(50),
      result_3dsecure VARCHAR(50),
      card_number VARCHAR(50),
      t_date VARCHAR(20),
      response TEXT,
      reversal_amount INT(10),
      makeDMS_amount INT(10),
      PRIMARY KEY (id)
      )
      ", $link);
mysql_query("CREATE TABLE /*!32312 IF NOT EXISTS*/ $db_table_batch  (
      id INT(10) NOT NULL AUTO_INCREMENT,
      result TEXT,
      result_code VARCHAR(3),
      count_reversal VARCHAR(10),
      count_transaction VARCHAR(10),
      amount_reversal VARCHAR(16),
      amount_transaction VARCHAR(16),
      close_date VARCHAR(20),
      response TEXT,
      PRIMARY KEY (id)
      )
      ", $link);
mysql_query("CREATE TABLE /*!32312 IF NOT EXISTS*/ $db_table_error  (
      id INT(10) NOT NULL AUTO_INCREMENT,
      error_time VARCHAR (20),
      action VARCHAR (20),
      response TEXT,
      PRIMARY KEY (id)
      )
      ", $link);

?>