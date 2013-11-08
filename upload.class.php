<?php
/////////////// Upload SLIDE-PPT CLASS ////////////////
//***********************************************
//************************************************
//**************   CREATED BY  *******************
//************ Andrey MK Ukraine,Kharkov,a2772@mail.ru **************
///////////////////////////////////////////////////
define('CLOUD_TOKEN','12345');
define('CLOUD_BASE','http://ppthtml2.cloudapp.net/');
define('ORG_APP_DELIM','_');
define('SLIDE_SLAG','preview/slide/');
define('PATH_LOG','C:/Windows/Temp/');
define('FILE_LOG','log_php.txt');
define("PATH_UPLOAD", "upload/");
define("PATH_SLIDERS", "preview/slide/");   //"output_html/sliders_html/"
define("PATH_PPTS", "preview/ppt/");

error_reporting(E_ALL);

class Upload {
  var $state="\n";
  var $fieldname;
  var $type;
  var $upload_dir;
  var $filename;
  var $slider_main_file;
  var $uploaded_file_name;
  var $type_sf_file;//--html,zip,ppt
  var $cloud_toke='???';
  var $action='';
  var $org_id;
  var $app_id;
  var $slide_id;
  var $ppt_session_id;//--sessionId from active SF all
  var $schema_url;        //--SF soap schema url
  var $send_url;        //--SF soap endpoint post send url
  function __construct($n_fieldname, $n_type, $n_upload_dir) {
    $this->fieldname = $n_fieldname;
    $this->type = $n_type;
    $this->type_arr = array('application/ppt', 'application/pptx', 'application/vnd.ms-powerpoint');
    $this->upload_dir = $n_upload_dir;

  }
  function show_files() {
    $myDirectory = opendir($this->upload_dir);

    while ($entryName = readdir($myDirectory)) {
      $dirArray[] = $entryName;
    }
    closedir($myDirectory);
    $indexCount = count($dirArray);
    print ("<a href='".CLOUD_BASE."'>Home</a>");
    print ('<h1>Uploaded files list</h1>');
    Print ("$indexCount-2 files<br>\n");

    sort($dirArray);

    print ("<TABLE border=1 cellpadding=5 cellspacing=0 class=whitelinks>\n");
    print ("<TR><TH>Filename</TH><th>FileExt</th><th>Filesize</th></TR>\n");
    // loop through the array of files and print them all
    for ($index = 0; $index < $indexCount; $index++) {
     $cur_ext= pathinfo(getcwd()."/".$this->upload_dir.$dirArray[$index], PATHINFO_EXTENSION);
     if ( (!isset($_GET['all'])) && ($cur_ext=='zip')) { continue;}
     if ( (!isset($_GET['all'])) && ($cur_ext=='html')){ continue;}
     if ( (!isset($_GET['all'])) && ($cur_ext=='done')){ continue;}

       if (substr("$dirArray[$index]", 0, 1) != ".") { // don't list hidden files

        print ("<TR><TD><a href=\"$this->upload_dir$dirArray[$index]\">$dirArray[$index]</a></td>");
        print ("<td>");

        print ($cur_ext);

        print ("</td>");
        print ("<td>");
        print ((filesize(getcwd()."/".$this->upload_dir.$dirArray[$index])/1000)." kb");
        //print (getcwd()."/".$this->upload_dir.$dirArray[$index]);
        print ("</td>");
        print ("</TR>\n");

      }
    }
    print ("</TABLE>\n");
  }
function unzipFile($name, $dir_file_unzip) {
        $zip = new ZipArchive;
        $res = $zip->open($name);
        if ($res === TRUE) {
          $zip->extractTo($dir_file_unzip);
          $zip->close();
          $this->state .= 'data-unzip-done;';
        }
        else {
          $this->state .= "error-unzip-dir; dir_file_unzip=".$dir_file_unzip;
          error_log(__FUNCTION__.' '.$this->state);
        }
}
function deletePartFiles() {
  try {
    array_map('unlink', glob(PATH_UPLOAD."pt_*_".$this->org_id.ORG_APP_DELIM.$this->app_id.ORG_APP_DELIM."*"));
  } catch (Exception $e) {
      $this->state .= "error-delete-part-files";
      error_log(__FUNCTION__.' '.$this->state);
  }
}
function deleteFiles($tp_file) {
   try {
      if ($tp_file=='ppt'){
        $old_dir=getcwd().'/preview/'.$tp_file.'/'.$this->org_id.'/'.$this->app_id;
      }
      if ($tp_file=='slide'){
        $old_dir=getcwd().'/preview/'.$tp_file.'/'.$this->org_id.'/'.$this->app_id.'/'.$this->slide_id;
      }
      $deleted_dir=$old_dir.'-deleted';
      rename($old_dir,$deleted_dir);
   } catch (Exception $e) {
      $this->state.=";error-delete-$tp_file ". $e->getMessage();
      error_log('ERROR-deletFiles, dir='.$old_dir);
   }
}
function moveSlide(){
  $this->slide_id= (isset($_POST['slide_id']))?$_POST['slide_id']:null;
  $this->state .= "move slide data;";
  try {
   if (!is_dir(PATH_SLIDERS.$this->org_id)) {
          mkdir(PATH_SLIDERS.$this->org_id);
   }
   if (!is_dir(PATH_SLIDERS.$this->org_id.'/'.$this->app_id)) {
          mkdir(PATH_SLIDERS.$this->org_id.'/'.$this->app_id);
   }
   if (!is_dir(PATH_SLIDERS.$this->org_id.'/'.$this->app_id.'/'.$this->slide_id)) {
          mkdir(PATH_SLIDERS.$this->org_id.'/'.$this->app_id.'/'.$this->slide_id);
   }

   $path_sliders_org_app = PATH_SLIDERS. $this->org_id.'/'.$this->app_id;
   $upload_file_source   = $this->uploaded_file_name;
   $slider_main_file     = PATH_SLIDERS.$this->org_id.'/'.$this->app_id.'/'.$this->slide_id.'/index.html';
   if (file_exists($slider_main_file)) {
        $dt = date("D M d, Y G:i");
        //$dt=time();
        unlink($slider_main_file);
        //rename($slider_main_file,"index_old.html");
   }

   if (copy( $upload_file_source, $slider_main_file)) {
      //unlink($upload_file_source);
      $this->slider_main_file=$slider_main_file;
      //$this->setBaseTagSlide();
   }
   else {
      $this->state .= ';error-copy-html:$this->org_id';
   }
   $this->state="done";
   }  catch (Exception $e) {
      $this->state.=';error-move-data '. $e->getMessage();
      error_log(__FUNCTION__.' '.$this->state);
   }
}
function moveData(){
  $this->state.= ";move data";
  try {
  if (!is_dir(PATH_SLIDERS.$this->org_id)) {
          mkdir(PATH_SLIDERS.$this->org_id);
   }
   if (!is_dir(PATH_SLIDERS.$this->org_id.'/'.$this->app_id)) {
          mkdir(PATH_SLIDERS.$this->org_id.'/'.$this->app_id);
   }
   if (!is_dir(PATH_SLIDERS.$this->org_id.'/'.$this->app_id.'/sources')) {
          mkdir(PATH_SLIDERS.$this->org_id.'/'.$this->app_id.'/sources');
   }
   $path_sliders_org_app = PATH_SLIDERS. $this->org_id.'/'.$this->app_id.'/sources';
   if  (($this->type_sf_file=='zip')&&(preg_match('/source/i',$this->uploaded_file_name))) {
         //---unzip source
         $this->unzipFile( $this->uploaded_file_name, $path_sliders_org_app);
         //---unzip jslib
         $this->unzipFile(PATH_UPLOAD.'JSLibrary.zip', $path_sliders_org_app);
   }
   /*
   if (copy( $upload_file_source, $slider_main_file)) {
      unlink($slider_upload_file);
   }
   else {
      $this->state .= ';error-copy-html:$this->org_id';
   }
   */
   $this->state="done";
   } catch (Exception $e) {
      $this->state.=';error-move-data '. $e->getMessage();
      error_log(__FUNCTION__.' '.$this->state);
   }
   //echo $this->state;
}
function pickUpData(){
   try {
   $part_files=glob(PATH_UPLOAD."pt_*_".$this->org_id.ORG_APP_DELIM.$this->app_id.ORG_APP_DELIM."*",GLOB_NOSORT);
   sort($part_files);
   file_put_contents(PATH_LOG.FILE_LOG, var_export($part_files));
   $cur_part=1;
   for ($i=0;$i<count($part_files);$i++) {
      $cur_ext= pathinfo(getcwd()."/".$part_files[$i], PATHINFO_EXTENSION);
      if ($cur_ext=='done') {continue;}
      $file_name_str=str_replace('upload/','',$part_files[$i]);
      $file_name_arr=explode(ORG_APP_DELIM,$file_name_str);
      list($pt,$pt_n,$pt_org,$pt_app,$f_name)=explode(ORG_APP_DELIM,$file_name_str);
      if (($pt=='pt')&&($pt_org==$this->org_id)&&($pt_app==$this->app_id)&&($pt_n==$cur_part)) {
            $f_add  = PATH_UPLOAD."pt_".$cur_part.'_'.$this->org_id.'_'.$this->app_id.'_'.$f_name;
            $f_itog = PATH_UPLOAD.$this->org_id.ORG_APP_DELIM.$this->app_id.ORG_APP_DELIM.$f_name;
            file_put_contents($f_itog,file_get_contents($f_add),FILE_APPEND);
            $cur_part++;
      }
    }
    //---set to done
    $cur_part=0;
    if (file_exists(getcwd()."/".$f_itog)) {
       for ($i=0;$i<count($part_files);$i++) {
            $cur_ext= pathinfo(getcwd()."/".$part_files[$i], PATHINFO_EXTENSION);
            if ($cur_ext=='done') {continue;}
            $file_name_str=str_replace('upload/','',$part_files[$i]);
            $file_name_arr=explode(ORG_APP_DELIM,$file_name_str);
            list($pt,$pt_n,$pt_org,$pt_app,$f_name)=explode(ORG_APP_DELIM,$file_name_str);
            if (($pt=='pt')&&($pt_org==$this->org_id)&&($pt_app==$this->app_id)&&($pt_n==$cur_part)) {
                $f_part = PATH_UPLOAD."pt_".$cur_part.ORG_APP_DELIM.$this->org_id.ORG_APP_DELIM.$this->app_id.ORG_APP_DELIM.$f_name;
                $f_done =$f_part.'.done';
                rename($f_part,$f_done);
                $cur_part++;
            }
       }
    }
    $this->deletePartFiles();
   } catch (Exception $e) {
     $this->state.=';error-pickupdata '. $e->getMessage();
     error_log(__FUNCTION__.' '.$this->state);
   }
}
function setBaseTagSlide() {
/*   // First variant
// Create DOM from string
 $str=file_get_contents('msghistory.txt');
$html = str_get_html('<div id="hello">Hello</div><div id="world">World</div>');
$html->find('div', 1)->class = 'bar';
$html->find('div[id=hello]', 0)->innertext = 'foo';
echo $html; // Output: <div id="hello">foo</div><div id="world" class="bar">World</div>
*/
/*   // Second variant
     $base_tag='<base href=""';
     $str=file_get_contents($this->slider_main_file);
     //$str=str_replace("$base_tag", "$deletedFormat",$str);
     $cloud_slide_base="http://ppthtml2/preview/slide/".$this->org_id."/".$this->app_id."/sources/";
     $str=str_replace("%base%", "href=''", "<base href='%$cloud_slide_base%'>");
     file_put_contents($this->slider_main_file, $str);
     $slide_file=PATH_SLIDERS.SLIDE_SLAG.$this->org_id.'/'.$this->app_id.'/'.$this->slide_id.'/index.html';
*/
     // third variant
     $html = new DOMDocument();
     $html->loadHTMLFile($slide_file);
     $html->getElementByTagName('base')->setAttribute('href',CLOUD_BASE.SLIDE_SLAG.$this->org_id.'/'.$this->app_id.'/sources');
     $html->saveHTMLFile($slide_file);
}
function setTypeSfFile($to) {
   $cur_ext= pathinfo($to, PATHINFO_EXTENSION);
   if (in_array($cur_ext,array('ppt','pptx'))) {
        $this->type_sf_file ='ppt';
   }
   if ($cur_ext=='html') {
        $this->type_sf_file ='html';
   }
   if ($cur_ext=='zip') {
        $this->type_sf_file ='zip';
   }
}
function uploadFromForce(){
  if ($_POST['cloud_token']== CLOUD_TOKEN){
      try {
        $this->cloud_token=$_POST['cloud_token'];
        if (isset($_POST['org_id'])) {
              $this->org_id  = $_POST['org_id'];
        }
        if (isset($_POST['app_id'])) {
              $this->app_id  = $_POST['app_id'];
        }
        if (isset($_POST['slide_id'])) {
              $this->slide_id= $_POST['slide_id'];
        }
        $from    = $_FILES[$this->fieldname]["tmp_name"];
        $to      = $_FILES[$this->fieldname]["name"];
        $this->setTypeSfFile($to);
        $this->uploaded_file_name=$to;
      } catch (Exception $e) {
        $this->state.=';error-input-data';
        error_log(__FUNCTION__.'error input data: '.$e->getMessage());
      }
      $part='';
      if (isset($_POST['action'])&&(preg_match('/delete/i',$_POST['action']))){
          if (preg_match('/ppt|pptx/i',$_POST['action'])) {
                $this->deleteFiles('ppt');
          }
          if (preg_match('/slide|slider/i',$_POST['action'])) {
                $this->deleteFiles('slide');
          }
          $this->state.=';done';
          echo $this->state;
          return; //--EXIT from protocol--
      }
      if (isset($_POST['data_part'])){
          $part='pt_'.$_POST['data_part'].'_';
          if ($_POST['data_part']==0) {
              $this->pickUpData();
              $this->moveData();
              $this->writePptParams();
              return;
         }
      }
      $move_to_file= PATH_UPLOAD. $part.$this->org_id.'_'.$this->app_id.'_';
      if (isset($_POST['slide_id'])) {

         $move_to_file=PATH_UPLOAD.$part.$this->org_id.'_'.$this->app_id.'_'.$this->slide_id.'_'.$to;
      } else {
         $move_to_file=$move_to_file.$to;
      }
      $this->uploaded_file_name=$move_to_file;
      if ($this->type_sf_file=='ppt') {
         //--activity for ppt
         $move_to_file=PATH_UPLOAD.$part.$this->org_id.'_'.$this->app_id.'_'.$to;
      }
      if (move_uploaded_file($from, $move_to_file)) {
         //echo "Uploaded..";
         $this->uploaded_file_name=$move_to_file;
         if (isset($_POST['slide_id'])) {
            $this->moveSlide();
         } else {
            $this->moveData();

         }
         //---extract and write json-data for ppt soap call
         //if (isset($_POST['ppt_session_id'])&&(isset($_POST['schema_url']))) {
          // $this->ppt_session_id=$_POST['ppt_session_id'];
         //  $this->schema_url=$_POST['schema_url'];
         // $this->send_url=$_POST['send_url'];
         $this->writePptParams();
         //}
         $this->state='done';

      } else {
          $this->state.="error: can't upload data,org_id=$this->org_id,app_id=$this->app_id";
          error_log(__FUNCTION__.' '.$this->state);
        }
   echo $this->state;
  }

}
function writePptParams(){

  try {
    if (isset($_POST['ppt_session_id'])&&(isset($_POST['schema_url']))) {
    $this->ppt_session_id=$_POST['ppt_session_id'];
    $this->schema_url=$_POST['schema_url'];
    $this->send_url=$_POST['send_url'];
    if (!is_dir(PATH_PPTS.$this->org_id.'/'.$this->app_id)) {
       if (!isset($this->org_id)) {
           error_log(__FUNCTION__.' EMPTY org_id='.$this->org_id);
       }
       if (!isset($this->app_id)) {
           error_log(__FUNCTION__.' EMPTY app_id='.$this->org_id);
       }
       mkdir(PATH_PPTS.$this->org_id.'/'.$this->app_id);
       $this->state.=";warring-ppt_params-no dir";
    }
    $cur_path_ppt=PATH_PPTS.$this->org_id.'/'.$this->app_id;
    $json_str='{"ppt_session_id":"'.$this->ppt_session_id.'","schema_url":"'.$this->schema_url.'","send_url":"'.$this->send_url.'"}';
    $content='<?php'.chr(10).chr(13);
    $content.='$ppt_params='."'".$json_str."';"."\n\n";
    $content.='var_dump($ppt_params);'."\n\n";
    $content.='?>';

    file_put_contents($cur_path_ppt.'/ppt_params.php',$content);
    file_put_contents($cur_path_ppt.'/ppt_params.json',$json_str);
    }
    } catch (Exception $e) {
        $this->state.=';error-writePptParams '. $e->getMessage();
        error_log(__FUNCTION__.' '.$this->state);
   }

}
function uploaded() {
  if (isset($_POST['cloud_token'])) {
      $this->uploadFromForce();
      return ;
  }
  if (1 == 1) {
      //--check the same name---
      //if ($_FILES[$this->fieldname]["error"] == 0) {
      echo "<b>File name: </b>" . $_FILES[$this->fieldname]["name"] . "<br />";
      echo "<b>File type: </b>" . $_FILES[$this->fieldname]["type"] . "<br />";
      echo "<b>File size: </b>" . (($_FILES[$this->fieldname]["size"] / 1024) / 1024) . " Mb<br />";
      //echo "<b>File Tmp: </b>" . $_FILES[$this->fieldname]["tmp_name"] . "<br />";
      if (move_uploaded_file($_FILES[$this->fieldname]["tmp_name"], $this->upload_dir . $_FILES[$this->fieldname]["name"])) {
        echo "Uploaded..";
      }
    //}
    else {
      //echo "Error: " . $_FILES[$this->fieldname]["error"] . "<br />";
      echo "Error: " . var_dump($this->fieldname). "<br />";
      error_log(__FUNCTION__.' '.$this->state);
    }
  }
  else {
    echo "Wrong file type..";
  }
  $this->show_files();
}
 }
?>
