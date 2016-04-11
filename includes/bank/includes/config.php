<?PHP



$ecomm_server_url = 'https://secureshop-test.firstdata.lv:8443/ecomm/MerchantHandler';
$ecomm_client_url = 'https://secureshop-test.firstdata.lv/ecomm/ClientHandler';
$cert_url = '/var/www/showroom/test.hostel/data/keys/IG000034_imakstore_test.pem'; //full path to keystore file
$cert_pass = 'testpass'; //keystore password
$currency = '941'; //978=EUR 840=USD 941=RSD 703=SKK 440=LTL 233=EEK 643=RUB 891=YUM
/*UNCOMMENT THIS WHEN YOU GO TO PRODUCTION SYSTEM, ALSO CHANGE KEYSTORE AND PASSWORD



$ecomm_server_url     = 'https://secureshop.firstdata.lv:8443/ecomm/MerchantHandler';

$ecomm_client_url     = 'https://secureshop.firstdata.lv/ecomm/ClientHandler';



*/
//MYSQL config
//!!!!! DO NOT CREATE DATABASE OR TABLE YOURSELF, IT WILL BE DONE AUTOMATICALY. CHANGE ONLY USER, PASS, HOST. !!!!!
$db_user = 'mrmvirt_hostelTr';
$db_pass = 'bXQsHBN!@Jsr';
$db_host = 'localhost';
$db_database = 'mrmvirt_hostelTransactions';
$db_table_transaction = 'transaction';
$db_table_batch = 'batch';
$db_table_error = 'error';

?>