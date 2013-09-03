<?php
define("PATH_UPLOAD", "upload/");
define('PATH_LOG','C:/Windows/Temp/');
define('FILE_LOG','log_php.txt');
define('ORG_APP_DELIM','_');
$org_id='00DG0000000CkUdMAK';
$app_id='a01G000000DTkc0IAD';
$part_files=glob(PATH_UPLOAD."pt_*_".$org_id."_".$app_id."_*",GLOB_NOSORT);
sort($part_files);
//file_put_contents(PATH_LOG.FILE_LOG, '<?php return '.var_export($part_files).";\n");
file_put_contents(PATH_LOG.FILE_LOG,  json_encode($part_files));
var_dump($part_files);
$cur_part=1;
for ($i=0;$i<count($part_files);$i++) {

  $file_name_str=str_replace('upload/','',$part_files[$i]);
  $file_name_arr=explode(ORG_APP_DELIM,$file_name_str);
  list($pt,$pt_n,$pt_org,$pt_app,$f_name)=explode(ORG_APP_DELIM,$file_name_str);
  //echo "<br>".$file_name_arr[2];
  echo "<br>--------------------org_id=".$pt_org;
  echo "<br>--------------------app_id=".$app_id;
  echo "<br>--------------------pt_n=".$pt_n;
  echo "<br>--------------------name=".$f_name;
  if (($pt=='pt')&&($pt_org==$org_id)&&($pt_app==$app_id)&&($pt_n==$cur_part)) {

            $f_add  = PATH_UPLOAD."pt_".$cur_part.'_'.$org_id.'_'.$app_id.'_'.$f_name;
            $f_itog = PATH_UPLOAD.$org_id.ORG_APP_DELIM.$app_id.ORG_APP_DELIM.$f_name;
            file_put_contents($f_itog,file_get_contents($f_add),FILE_APPEND);
            $cur_part++;
            echo "<br> cur_part=".$cur_part;
  }
}
 //---set to done
 $cur_part=1;
 echo "<br> f_itog=".$f_itog;
  if (file_exists(getcwd()."/".$f_itog)) {
     echo "<br>exist";
     for ($i=1;$i<count($part_files);$i++) {
          $cur_ext = pathinfo(getcwd()."/".$part_files[$i], PATHINFO_EXTENSION);
          echo "<br> cur_ext=".$cur_ext."  i=".$i;
          if ($cur_ext=='done') {continue;}
          $file_name_str=str_replace('upload/','',$part_files[$i]);
          $file_name_arr=explode(ORG_APP_DELIM,$file_name_str);
          list($pt,$pt_n,$pt_org,$pt_app,$f_name)=explode(ORG_APP_DELIM,$file_name_str);
          if (($pt=='pt')&&($pt_org==$org_id)&&($pt_app==$app_id)&&($pt_n==$cur_part)) {
              $f_part = PATH_UPLOAD."pt_".$cur_part.ORG_APP_DELIM.$org_id.ORG_APP_DELIM.$app_id.ORG_APP_DELIM.$f_name;
              $f_done =$f_part.'.done';
              rename($f_part,$f_done);
              echo "<br> done  f_done=".$f_done;
              $cur_part++;
          }
     }
  }
?>