<?php
define("PATH_SLIDERS", "preview/ppt/");
$slide_name=$token=$org_id=$app_id="";

if (isset($_GET['slide_name'])) {
    $slide_name=$_GET['slide_name'];
}
if (isset($_GET['token'])) {
    $token=$_GET['token'];
}
if (isset($_GET['org_id'])) {
    $org_id=$_GET['org_id'];
}
if (isset($_GET['app_id'])) {
    $app_id=$_GET['app_id'];
}
require_once "sftk.php";
$az_store= new AzureStore();
$file=getcwd()."/ctmobile.app@2x.png";
if (($org_id !="") && ($app_id !="")) {
      if ($az_store->findSlideByOrgApp($org_id,$app_id) ) { //---by org app
          outputImg('OrgApp');
      }
}elseif ($az_store->findSlideByToken($token) ) { //---by token
     outputImg('token');
} elseif ($az_store->findSlideByOrgApp($org_id,$app_id)) {//---by org_id,app_id
     outputImg('token-not-OrgApp-try');
    exit;
}
header("Content-Type: image/png");
header("Content-Length: " . filesize($file));
readfile($file);
//-----------------------------------------------output----------------------
function outputImg($by_what) {
  global $az_store,$slide_name,$token;
    $file=getcwd()."/".PATH_SLIDERS.$az_store->org_id."/".$az_store->app_id."/sliders/".$slide_name;
    if (file_exists($file)) {
        $filename = basename($file);
        $file_extension = strtolower(substr(strrchr($filename,"."),1));
        error_log(__FUNCTION__.' By '.$by_what.' '.$token.' show file: '.$file);
        $ctype="image/jpg";
        switch( $file_extension ) {
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            default:
        }
        header("Content-Type: " . $ctype);
        header("Content-Length: " . filesize($file));
        readfile($file);
        exit;
     } else {
        error_log(__FUNCTION__.' no file: '.$file);
        $file=getcwd()."/ctmobile.app@2x.png";
    }
}
?>