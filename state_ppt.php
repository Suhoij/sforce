<?php
define('PATH_STATE','log/');
try {
 $filename_arr = glob(PATH_STATE."state_ppt.*",GLOB_NOSORT);
 $res=file_get_contents(getcwd().'/'.$filename_arr[0]);
} catch (Exception $e){
    $error_mes=$e->getMessage();
    error_log(__FILE__.' '.$error_mes);
    $res=json_encode(array('state'=>'error','job'=>'state-ppt-read','mes'=>$error_mes,'time'=>date("d/m/H/i/s")));
}
echo $res;
?>