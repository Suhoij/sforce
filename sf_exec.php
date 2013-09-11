<?php
include "sf_ext.php";
$sessionId='00DG0000000CkUd!AQ0AQHtb7v7X.MLjW24R6JvPeMQCXAwLkcPiB7DoZa39GuaUDhVHnqQsavHESXUlLGydy2e2zOoUBVpvtMQapJw6TiFfbWSo';
//$sf_url= 'https://na11.salesforce.com';
//$sf_url='https://soap.sforce.com/schemas/class/HelperClass';
$sf_url='https://na11.salesforce.com/services/Soap/class/HelperClass';
$sf=new SfExt($sessionId,$sf_url);
//$sf=new SfExt();  //---PARTNER WSDL
$code_sf=' presentationUploaded(63)';
$sf->execSfCode($code_sf);
echo $sf->state;

?>