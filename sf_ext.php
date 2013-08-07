<?php
  define("USERNAME", "e-detmobile@customertimes.com");
  define("PASSWORD", "poqw09123");
  define("SECURITY_TOKEN", "vYwfdDbmbtdJI7gRnlJZLwIe");
  define("PATH_TOOLKIT","sfextapp/Force.com-Toolkit-for-PHP/");
  define("PATH_UPLOAD","upload/");
  define("PATH_UNZIP","output_html/html_zip/");
  define("PATH_SLIDERS","output_html/sliders_html/");
  require_once (PATH_TOOLKIT.'soapclient/SforcePartnerClient.php');
  class SfExt {
    var $mySforceConnection; //---connection to SalesForce--
    var $state;
    var $data;
    var $f_id;
    var $f_name;
    function __construct() {
        $this->f_id=$_GET['f_id'];
	    $this->mySforceConnection = new SforcePartnerClient();
	    $this->mySforceConnection->createConnection(PATH_TOOLKIT."partner.wsdl.xml");
	    $this->mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
        //echo "<br> login done ";//var_dump($this->mySforceConnection);
        $this->state='login';
    }
    public function takeFile($f_id) {
       try {
        //---take body----
        $query    = "SELECT Body FROM Attachment WHERE Id='$this->f_id'";
        //echo "<br> takeFile q=$query";
	    $response = $this->mySforceConnection->query($query);
        $this->data = base64_decode(strip_tags($response->records[0]->any));
        $this->state='get_data';
        //echo $this->data;
        } catch (Exception $e) {
            $this->state='error-'.$e->getMessage();
            echo   $this->state;
        }
    }
    public function takeName($f_id) {
     try {
       //---take name---
        $query    = "SELECT Name FROM Attachment WHERE Id='$f_id'";
	    $response = $this->mySforceConnection->query($query);
        $this->f_name   =  $response->records[0]->any;
     } catch (Exception $e) {
            $this->state='error-name-'.$e->getMessage();
     }
    }
    public function saveFile(){
    try {
      if(preg_match('/error/',$this->state)) return;
       //---save file---
        if (!empty($this->f_name)) {
            $f_full_name=getcwd()."\\upload\\".$this->f_id.'_'.$this->f_name;

           if (empty($this->data)){
                  $this->state='error-file-data';
           } else {
               file_put_contents($f_full_name,$this->data);
                $this->state='done;';
           }
        }
    } catch (Exception $e) {
            $this->state='error-save-file-'.$e->getMessage();
     }
  }
  public function unzipFile($name,$dir_file_unzip) {
      $zip = new ZipArchive;
      $res = $zip->open($name);
      if ($res === TRUE) {
        $zip->extractTo($dir_file_unzip);
        $zip->close();
        //echo '<br>UNZIP done!';
        $this->state.='data-unzip-done;';
      } else {
        $this->state.="error-unzip-dir;"; ;
      }
  }
  public function createSliderHTML(){
   try {
   if(preg_match('/error/',$this->state)) return;
    //--create dir
    mkdir(PATH_SLIDERS.$this->f_id);
    $slider_upload_file=PATH_UPLOAD.$this->f_id.'_index.html';
    $slider_main_file=PATH_SLIDERS.$this->f_id.'/index.html';
    //---unzip source
    $this->unzipFile(PATH_UPLOAD.'sources.zip',PATH_SLIDERS.$this->f_id);
    //---unzip jslib
    $this->unzipFile(PATH_UPLOAD.'JSLibrary.zip',PATH_SLIDERS.$this->f_id);
    if (copy(PATH_UPLOAD.$this->f_id.'_index.html',$slider_main_file)) {
         unlink($slider_upload_file);


    } else {
      $this->state.='error-copy-html';
    }
    //--move file to dir
    }catch (Exception $e) {
            $this->state.='error-create-file-'.$e->getMessage();
    }
  }
 }

?>