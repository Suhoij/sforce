<?php
$state='init';
if (isset($_GET['f_id'])&&(isset($_GET['f_name']))) {
  $state='start';
  $f_id=$_GET['f_id'];
  $f_name=$_GET['f_name'];
  define("USERNAME", "e-detmobile@customertimes.com");
  define("PASSWORD", "poqw09123");
  define("SECURITY_TOKEN", "vYwfdDbmbtdJI7gRnlJZLwIe");
  define("PATH_TOOLKIT","sfextapp/Force.com-Toolkit-for-PHP/");
  define("PATH_UNZIP","output_html/html_zip/");
  require_once (PATH_TOOLKIT.'soapclient/SforcePartnerClient.php');

  $mySforceConnection = new SforcePartnerClient();
  $mySforceConnection->createConnection(PATH_TOOLKIT."partner.wsdl.xml");
  $mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
  echo "<br> login done ";

  //$f_id='00PG0000008Cz7j';
  $query    = "SELECT Body FROM Attachment WHERE Id='$f_id'";
  echo "<br> takeFile q=$query";
  $response = $mySforceConnection->query($query);
  var_dump($response);
  $data     = base64_decode(strip_tags($response->records[0]->any));
  $state='data-sf-done';
  //echo "<br> data=data";

  //sleep(2);
  //---take name---
  //$query    = "SELECT Name FROM Attachment WHERE Id='$f_id'";
  //$response = $this->mySforceConnection->query($query);
  //$f_name   =  $response->records[0]->any;
  //echo "<br> f_name=$f_name";

  //-----------------------------

  $f_full_name=getcwd()."\\upload\\$f_name";

  $handle = fopen($f_full_name, 'w');
  $res=fwrite($handle, $data);
  fclose($handle);
  $state.='data-file-write;';
  //----unzip----------------
  if (strpos($f_name,'.zip')!=false) {
      $dir_file_unzip=PATH_UNZIP."/$f_id/";
      if (!mkdir($dir_file_unzip)) {

         $state.="error-dir;";
       } else {

            $zip = new ZipArchive;
            $res = $zip->open($f_full_name);
            if ($res === TRUE) {
              $zip->extractTo($dir_file_unzip);
              $zip->close();
              //echo '<br>UNZIP done!';
              $state.='data-unzip-done;';
            } else {
              $state.="error-unzip-dir;"; ;
            }

      }
  }


 }
   echo "{'state':'$state'}";
?>