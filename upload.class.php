<?php
/////////////// Upload FILE CLASS ////////////////
//***********************************************
//************************************************
//************** CREATED BY RK *******************
//************ Owner of Tracepk.net **************
///////////////////////////////////////////////////
define('CLOUD_TOKEN','12345');
define("PATH_UPLOAD", "upload/");
define("PATH_SLIDERS", "output_html/sliders_html/");
error_reporting(E_ALL);

class Upload {
  var $state;
  var $fieldname;
  var $type;
  var $upload_dir;
  var $filename;
  var $cloud_toke='???';
  var $org_id;
  var $app_id;
  var $slide_id;
  function __construct($n_fieldname, $n_type, $n_upload_dir) {
    $this->fieldname = $n_fieldname;
    $this->type = $n_type;
    $this->type_arr = array('application/ppt', 'application/pptx', 'application/vnd.ms-powerpoint');
    $this->upload_dir = $n_upload_dir;
    //$this->filename = $n_filename;
    //$this->uploaded();
  }
  function show_files() {
    $myDirectory = opendir($this->upload_dir);
    //print_r(getcwd());
    // get each entry
    while ($entryName = readdir($myDirectory)) {
      $dirArray[] = $entryName;
    }
    // close directory
    closedir($myDirectory);
    //	count elements in array
    $indexCount = count($dirArray);
    //print ("<a href='http://ppt-to-html.cloudapp.net'>Home</a>");
    print ("<a href='http://ppthtml2.cloudapp.net'>Home</a>");
    print ('<h1>Uploaded files list</h1>');
    Print ("$indexCount-2 files<br>\n");
    // sort 'em
    sort($dirArray);
    // print 'em
    print ("<TABLE border=1 cellpadding=5 cellspacing=0 class=whitelinks>\n");
    print ("<TR><TH>Filename</TH><th>FileExt</th><th>Filesize</th></TR>\n");
    // loop through the array of files and print them all
    for ($index = 0; $index < $indexCount; $index++) {
      if (substr("$dirArray[$index]", 0, 1) != ".") { // don't list hidden files
        print ("<TR><TD><a href=\"$this->upload_dir$dirArray[$index]\">$dirArray[$index]</a></td>");
        print ("<td>");
        //print (filetype(getcwd()."/".$dirArray[$index]));
        print (pathinfo(getcwd()."/".$this->upload_dir.$dirArray[$index], PATHINFO_EXTENSION));
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
function moveSlide(){

}
function moveData(){
  try {
  if (!is_dir(PATH_SLIDERS.$this->org_id)) {
          mkdir(PATH_SLIDERS.$this->org_id);
   }
   if (!is_dir(PATH_SLIDERS.$this->org_id.'/'.$this->app_id)) {
          mkdir(PATH_SLIDERS.$this->org_id.'/'.$this->app_id);
   }
   $path_sliders_org_app = PATH_SLIDERS. $this->org_id.'/'.$this->app_id;
   $upload_file_source = PATH_UPLOAD.'sources.zip'.$this->app_id.'_sources.zip';
   $slider_main_file = PATH_SLIDERS.$this->f_id.'/index.html';
   //---unzip source
   $this->unzipFile(PATH_UPLOAD.'sources.zip', $path_sliders_org_app);
   //---unzip jslib
   $this->unzipFile(PATH_UPLOAD.'JSLibrary.zip', $path_sliders_org_app);
   if (copy(PATH_UPLOAD . $this->org_id.'_'.$this->app_id.'_index.html', $slider_main_file)) {
      unlink($slider_upload_file);
   }
   else {
      $this->state .= ';error-copy-html:$this->org_id';
   }
   } catch (Exception $e) {
      $this->state.=';error-move-data '. $e->getMessage();
   }
}
function pickUpData(){

}
function uploadFromForce(){
  if ($_POST['cloud_token']== CLOUD_TOKEN){
      $this->cloud_token=$_POST['cloud_token'];
      $this->org_id  = $_POST['org_id'];
      $this->app_id  = $_POST['app_id'];
      $this->slide_id= $_POST['slide_id'];
      $from    = $_FILES[$this->fieldname]["tmp_name"];
      $to      = $this->upload_dir . $_FILES[$this->fieldname]["name"];
      $part='';
      if (isset($_POST['data_part'])){
          $part='pt_'.$_POST['data_part'].'_';
      }
      if (move_uploaded_file($from, $part.$this->org_id.'_'.$this->app_id.'_'. $to)) {
        //echo "Uploaded..";
         if (isset($_POST['slide_id'])) {
            $this->moveSlide();
         } else {
           if (isset($_POST['data_part'])&&($_POST['data_part']==0)) {
               $this->pickUpData();
           } else {
               $this->moveData();
            }
         }

      } else "error: can't upload data,org_id=$this->org_id,app_id=$this->app_id";
  }

}
function uploaded() {
 echo " 1)PARAMS: cloud_token=$this->cloud_token org_id=$this->org_id ; app_id=$this->app_id file_name=".$_FILES[$this->fieldname]['name'];
if (isset($_POST['cloud_token'])) {
    $this->uploadFromForce();
    echo " 2)PARAMS: cloud_token=$this->cloud_token org_id=$this->org_id ; app_id=$this->app_id file_name=".$_FILES[$this->fieldname]['name'];
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
    }
  }
  else {
    echo "Wrong file type..";
  }
  $this->show_files();
}
 }
?>
