<?php
define("USERNAME", "e-detmobile@customertimes.com");
define("PASSWORD", "poqw0912");
define("SECURITY_TOKEN", "bU6O5MjMDlB0H8USB7j4C1Ul0");
define("PATH_TOOLKIT", "Force.com-Toolkit-for-PHP/");
define("PATH_UPLOAD", "upload/");
define("PATH_SLIDE", "preview/slide/");


require_once (PATH_TOOLKIT . 'soapclient/SforcePartnerClient.php');

//----------------------------
//$_FROM=$_POST;
$_FROM=$_GET;
if (isset($_FROM['action'])&&($_FROM['action']=='getPlaceVar')) {
    $org_id=$_FROM['org_id'];
    $app_id=$_FROM['app_id'];
    $slide_id=$_FROM['slide_id'];
    //------Check slide in SF
    $file_json_var=PATH_SLIDE.$org_id."/".$app_id."/".$slide_id.'/index.html';
    $index_html=file_get_contents($file_json_var);
    preg_match('/clmPlaceholderList\s=(.*)<\/script/sei',$index_html,$clmPlaceHolderList);
    //var_dump($clmPlaceHolderList);

    //print_r($clmPlaceHolderList[1]);
    //echo base64_encode($clmPlaceHolderList[1]);
    echo json_encode($clmPlaceHolderList[1]);
}
if (isset($_FROM['action'])&&($_FROM['action']=='checkSlide')) {
      $mySforceConnection = new SforcePartnerClient();
      $mySforceConnection->createConnection(PATH_TOOLKIT."partner.wsdl.xml");
      $mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
      echo "<br> login done ";
      $cur_slide_id ="a00G000000NFoz5IAD";
      $cur_slide_id =$_FROM['slide_id'];
      //$cur_slide_id ="12345";

      $query    = "select id,SlideId__c from Widget__c where SlideId__c='$cur_slide_id'";
      $response = $mySforceConnection->query($query);
      //var_dump($response);
      //$data     = base64_decode(strip_tags($response->records[0]->any));
      //$res     = $response->records[0]->any["expr0"];
      $res     = $response->records[0]->any;
      //var_dump($res);
      $state='data-sf-done;';

      if (isset($res)) {
          echo "<br><br><br> Res=$res";
      } else {
         echo "<br><br><br> No slide with SlideId=$cur_slide_id";
      };
}

?>