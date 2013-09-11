<?php
define("USERNAME", "e-det@customertimes.com");//e-detmobile@customertimes.com
define("PASSWORD", "poqw09123");  //9123   //swatch13   //poqw0912
define("SECURITY_TOKEN", "ZElP3rrmQjZhVzgawVOX6zgn9"); //Bn7D3AJssElaQ4HJaOdekMio2    //vYwfdDbmbtdJI7gRnlJZLwIe

define("PATH_TOOLKIT", "Force.com-Toolkit-for-PHP/");
define("PATH_UPLOAD", "upload/");
define("PATH_UNZIP", "output_html/html_zip/");
define("PATH_SLIDERS", "output_html/sliders_html/");
require_once (PATH_TOOLKIT . 'soapclient/SforcePartnerClient.php');
class SfExt {
  var $mySforceConnection; //---connection to SalesForce--
  var $state;
  var $data;
  var $f_id;
  var $f_name;
  var $sf_url;
  var $session_id;
  var $sliders_id_force = array();
  var $sliders_id_cloud = array();
  var $sliders_absent = array();
  function __construct($session_id='',$sf_url='') {
     try {
      $this->f_id = $_GET['f_id'];
      $this->session_id=$session_id;
      $this->sf_url=$sf_url;



      if (!empty($session_id)) {
        $this->postData(); die();
         //$this->mySforceConnection->attach( $this->sf_url, $this->session_id);
         $this->mySforceConnection = new SforceBaseClient();
         $this->mySforceConnection->namespace='http://soap.sforce.com/schemas/class/HelperClass';
          session_start();
          $this->mySforceConnection->createConnection(PATH_TOOLKIT . "sf-call-wsdl.xml");
          //$this->mySforceConnection->login(USERNAME, PASSWORD . SECURITY_TOKEN);
          $this->mySforceConnection->setEndpoint($this->sf_url);
          $this->mySforceConnection->setSessionHeader($this->session_id);
         //$this->mySforceConnection = new SforcePartnerClient();
              //$this->mySforceConnection->createConnection(PATH_TOOLKIT . "sf-call-wsdl.xml");
         //$this->mySforceConnection->createConnection(PATH_TOOLKIT . "apex.wsdl.xml");
         //$this->mySforceConnection->createConnection(PATH_TOOLKIT . "partner-last.wsdl.xml");
         //$this->mySforceConnection->login(USERNAME, PASSWORD . SECURITY_TOKEN);
              //$this->mySforceConnection->setSessionHeader($this->session_id);
              //$this->mySforceConnection->setEndpoint($this->sf_url);
      } else {
        $this->mySforceConnection = new SforcePartnerClient();
        $this->mySforceConnection->createConnection(PATH_TOOLKIT . "partner.wsdl.xml");
        $this->mySforceConnection->login(USERNAME, PASSWORD . SECURITY_TOKEN);
        echo $this->mySforceConnection->getLocation();   die();
        $this->state = ';login';
      }
      } catch (Exception $e){
           $this->state = ';login-error-' . $e->getMessage();
           echo $this->state;
      }
  }
  public function execSfCode($str) {
    try {
      //$response = $this->mySforceConnection->query($str);
      //$url='http://na11.salesforce.com/services/Soap/class/HelperClass';
      //$url='http://soap.sforce.com/schemas/class/HelperClass';
      //$response = $this->mySforceConnection->setEndpoint($this->sf_url);
      //var_dump( $this->mySforceConnection);
      //$describe = $this->mySforceConnection->describeSObjects(array('Lead'));
      //print_r($describe);
     // $response = $this->mySforceConnection->describeGlobal();
      //$response = $this->mySforceConnection->presentationUploaded(2);
      //$query = "SELECT Body FROM Attachment WHERE Id='00PG0000008kLcZ'";
      //$query = "SELECT FirstName, LastName FROM Contact";

      //$response = $this->mySforceConnection->query($query);

      //$response = $this->mySforceConnection->getLocation();
             //$sforce_header = new SoapHeader("https://na11.salesforce.com/services/Soap/class/HelperClass", "SessionHeader", array( "sessionId" => $this->session_id ) );
             //var_dump($sforce_header);
             //$this->mySforceConnection->setHeaders( array( $sforce_header ) );
              //$response = $this->mySforceConnection;
              //$response = $this->mySforceConnection->getNamespace();
              //$response = $this->mySforceConnection->describeGlobal();
              //$response = $this->mySforceConnection->getFunctions();
              //$response = $this->mySforceConnection->getLocation();
              //$response = $this->mySforceConnection->getConnection();
              //$response = $this->mySforceConnection->presentationUploaded(62);
              //$response = $this->mySforceConnection->getTypes();
              //$response = $this->mySforceConnection->getSessionId();

              $response=$this->mySforceConnection->presentationUploaded(10);
               var_dump($response);

    } catch (Exception $e) {
       $this->state = ';execSfCode-error-' . $e->getMessage();
    }
  }
  public function postData(){
      $post_url='https://na11.salesforce.com/services/Soap/class/HelperClass';
      $post_data=file_get_contents(getcwd()."\sf-soap.txt");
      echo $post_data;
      $headers  =  array("Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: \"run\"",
            "Content-length: ".strlen($post_data)
       );
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$post_url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      //curl_setopt($ch, CURLOPT_POSTFIELDS,"postvar1=value1&postvar2=value2&postvar3=value3");
      curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $http_result = curl_exec($ch);
      $error = curl_error($ch);
      $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);

      curl_close($ch);
      //fclose($fp);

      print $http_code;
      print "<br />Result:<br />$http_result";
      if ($error) {
         print "<br />Error:<br />$error";
      }
  }
  public function takeFile($f_id) {
    try {
      //---take body----
      $query = "SELECT Body FROM Attachment WHERE ParentId='$this->f_id'";

      $response = $this->mySforceConnection->query($query);

      $this->data = base64_decode(strip_tags($response->records[0]->any));
      $this->state = 'get_data';

    }
    catch (Exception $e) {
      $this->state = ';error-' . $e->getMessage();
      echo $this->state;
    }
  }
  public function takeName($f_id) {
    try {
    //---take name---
      $query = "SELECT Name FROM Attachment WHERE ParentId='$f_id'";
      $response = $this->mySforceConnection->query($query);
      $this->f_name = $response->records[0]->any;
    }
    catch (Exception $e) {
      $this->state = 'error-name-' . $e->getMessage();
    }
  }
  public function saveFile() {
    try {
      if (preg_match('/error/', $this->state))
        return;
        //---save file---
      if (!empty ($this->f_name)) {
        $f_full_name = getcwd() . "\\upload\\" . $this->f_id . '_' . $this->f_name;
        if (empty ($this->data)) {
          $this->state = 'error-file-data';
        }
        else {
          file_put_contents($f_full_name, $this->data);
          $this->state = 'done;';
        }
      }
    }
    catch (Exception $e) {
      $this->state = 'error-save-file-' . $e->getMessage();
    }
  }
  public function unzipFile($name, $dir_file_unzip) {
    $zip = new ZipArchive;
    $res = $zip->open($name);
    if ($res === TRUE) {
      $zip->extractTo($dir_file_unzip);
      $zip->close();

      $this->state .= 'data-unzip-done;';
    }
    else {
      $this->state .= "error-unzip-dir;";

    }
  }
//---- $sf->takeSlidersId();
//--   $sf->findAbsentSliders();
//--   $sf->createAbsentSliders();
  public function takeSlidersId() {
    $query = "SELECT Id FROM Slide__c";

    $response = $this->mySforceConnection->query($query);

    $L = count($response->records);
    for ($i = 0; $i < $L; $i++) {
      array_push($this->sliders_id_force, $response->records[$i]->Id[0]);

    }
  }
  public function findAbsentSliders() {
    $dir = glob(getcwd() . '/output_html/sliders_html/*', GLOB_ONLYDIR);

    foreach ($dir as $item) {
      $s_arr = explode('/', $item);
      $s_id = $s_arr[count($s_arr) - 1];
      array_push($this->sliders_id_cloud, $s_id);
    }

    $L = count($this->sliders_id_force);

    for ($i = 0; $i < $L; $i++) {
      $s_id_force = $this->sliders_id_force[$i];
      if (!in_array($s_id_force,$this->sliders_id_cloud)) {
        echo "<br> Absent:".$s_id_force;
        array_push($this->sliders_absent, $s_id_force);
      }
    }


  }
  public function createAbsentSliders(){
    $cnt=0;
    $L=count($this->sliders_absent);
    for ($i = 0; $i < $L; $i++) {
        $this->f_id = $this->sliders_absent[$i];
        $this->createSliderHTML();
        sleep(2);
        $cnt++;
    }
     $this->state .=";done-create-absent(cnt=$cnt)";
  }
  //-----
  public function deleteFolder($folder) {
      $glob = glob($folder);
      foreach ($glob as $g) {
          if (!is_dir($g)) {
              unlink($g);
          } else {
              $this->deleteFolder("$g/*");
              rmdir($g);
          }
      }
  }
  public function createSliderHTML() {
    try {
      echo "<br> createSliderHTML ";
      echo "<br> state=$this->state";
      if (preg_match('/error/', $this->state))
        return;
        //--if dir_exist dir.clear
      if (is_dir(PATH_SLIDERS.$this->f_id)) {
          echo "<br> Delete folder  $this->f_id";
          $this->deleteFolder(PATH_SLIDERS . $this->f_id);
      }
      //--create dir
      mkdir(PATH_SLIDERS.$this->f_id);
      echo "<br> createDir  $this->f_id";
      $slider_upload_file = PATH_UPLOAD.$this->f_id.'_index.html';
      $slider_main_file = PATH_SLIDERS.$this->f_id.'/index.html';
      //---unzip source
      $this->unzipFile(PATH_UPLOAD.'sources.zip', PATH_SLIDERS . $this->f_id);
      //---unzip jslib
      $this->unzipFile(PATH_UPLOAD.'JSLibrary.zip', PATH_SLIDERS . $this->f_id);
      if (copy(PATH_UPLOAD . $this->f_id.'_index.html', $slider_main_file)) {
        unlink($slider_upload_file);
      }
      else {
        $this->state .= ';error-copy-html:$this->f_id';
      }
      //--move file to dir
    }
    catch (Exception $e) {
      $this->state .= ';$this->f_id:error-create-file-' . $e->getMessage();
      echo "<br> state=$this->state";
    }
    echo "<br> state=$this->state";
  }
}
?>