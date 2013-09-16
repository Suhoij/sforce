<?php
include "sf_ext.php";
$sessionId='00DG0000000CkUd!AQ0AQASO7j5Ae1w4EEXNK0SzBcDqAJUh7CiTdDb4Jlp_.XRM7qWOzPy4NBKokqrrOUMIj6295JXBP2ZdlgTqzUCCjXE6UNBJ';
//$sf_url= 'https://na11.salesforce.com';
//$sf_url='https://soap.sforce.com/schemas/class/HelperClass';
$sf_url='https://na11.salesforce.com/services/Soap/class/HelperClass';
$sf=new SfExt($sessionId,$sf_url);
//$sf=new SfExt();  //---PARTNER WSDL
$code_sf=' presentationUploaded(63)';
$sf->execSfCode($code_sf);
echo $sf->state;

?>