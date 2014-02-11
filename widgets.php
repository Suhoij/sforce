<?php
define("USERNAME", "e-detmobile@customertimes.com");
define("PASSWORD", "poqw0912");
define("SECURITY_TOKEN", "bU6O5MjMDlB0H8USB7j4C1Ul0");
define("PATH_TOOLKIT", "Force.com-Toolkit-for-PHP/");
define("PATH_UPLOAD", "upload/");
define("PATH_SLIDE", "preview/slide/");


require_once (PATH_TOOLKIT . 'soapclient/SforcePartnerClient.php');
require_once ('widgets_config.php');
//----------------------------
//$_FROM=$_POST;
$_FROM=$_GET;
$org_id=$_FROM['org_id'];
$app_id=$_FROM['app_id'];
$slide_id=$_FROM['slide_id'];
if (isset($_FROM['action'])&&($_FROM['action']=='getPlaceVar')) {

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
      $cur_slide_id ="a00G000000NF11eIAD";
      //$cur_slide_id =$_FROM['slide_id'];
      //$cur_slide_id ="12345";

      //$query    = "select id,SlideId__c from Widget__c where SlideId__c='$cur_slide_id'";
      $query    = "select id,Type__c,PlaceholderId__c,Data__c,Width__c,Height__c from Widget__c where SlideId__c ='$cur_slide_id'";
      $response = $mySforceConnection->query($query);
      //var_dump($response);
      //$data     = base64_decode(strip_tags($response->records[0]->any));
      //$res     = $response->records[0]->any["expr0"];
      $res     = $response->records[0]->any;
      //var_dump($res);
      $state='data-sf-done;';

      if (isset($res)) {
          print_r( "<pre><br><br><br> Res=$res</pre>");
      } else {
         echo "<br><br><br> No slide with SlideId=$cur_slide_id";
      };
     exit(0);
}
//---------------------------- CHECK if params points to valid path -------------
$_FROM=$_POST;
if (isset($_FROM['action'])&&($_FROM['action']=='isSlideValid')) {
     $dir=PATH_SLIDE.$org_id."/".$app_id."/".$slide_id;
     if (realpath($dir)) {
       echo 'yes';
     } else {echo 'no';}
};
//---------------------------sendWidgets------------------------------------------
if (isset($_FROM['action'])&&($_FROM['action']=='sendWidgets')) {
    $cur_slide_id= $_FROM['slide_id'] ;
    //$blocks_list = json_decode($_FROM['blocks_list']);
    $blocks_list = $_FROM['blocks_list'];
    error_log("\n\nSlide_id=".$_FROM['slide_id']);
    error_log(json_encode($blocks_list));
    error_log("\n\nCount=".count($blocks_list));
    error_log("\n\n0)Type=".$blocks_list[0]['type']);                           //---widget type--
    error_log("\n\n0)model_prop WidgetID=".$blocks_list[0]['model_prop']['WidgetID']);                           //---widget type--
    error_log("\n\n1)Type=".$blocks_list[1]['type']);
     error_log("\n\n1)model_prop WidgetID=".$blocks_list[1]['model_prop']['WidgetID']);                           //---widget type--
    //error_log("\n\nT0)Type=".$blocks_list[0]['model_prop']['IsActive']);        //---model data---
    //error_log("\n\nT0)Type=".$blocks_list[0]['data_coll_prop'][0]['data_name']);//---collection data
    // error_log("\n\n0)Type=".$blocks_list[1]['type']);
    // error_log("\n\nT0)ype=".$blocks_list[1]['model_prop']['IsActive']);
    echo json_encode($blocks_list);

    //-----SF UPDATE----------------------
    try {
        $mySforceConnection = new SforcePartnerClient();
        $mySforceConnection->createConnection(PATH_TOOLKIT."partner.wsdl.xml");
        $mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
        $blocks_cnt = count($blocks_list);
        //$records[0] = new SObject();
        /* $records[0]->Id = $ids[0];
          $records[0]->fields = array(
              'Phone' => '(415) 555-5555',
          );
          $records[0]->type = 'Contact';

          $records[1] = new SObject();
          $records[1]->Id = $ids[0];
          $records[1]->fields = array(
              'Phone' => '(415) 486-9969',
          );
          $records[1]->type = 'Contact';

          $response = $mySforceConnection->update($records);
          foreach ($response as $result) {
              echo $result->id . " updated<br/>\n";
          }*/
        $records = array();
        for ($i=0;$i<$blocks_cnt;$i++) {
        	$records[$i] = new SObject();
            $records[$i]->type = 'Widget__c';
            $records[$i]->id   = $blocks_list[$i]['model_prop']['WidgetID'];

            $filled_fields=Array();
            $widget_model_prop=$widgets_fields[$blocks_list[$i]['type']];
            foreach ($widget_model_prop as $w_field) {
              if ($w_field =='Data') {
                  if (isset($blocks_list[$i]['data_coll_prop'])) {
                      $filled_fields['Data__c'] = base64_encode(json_encode($blocks_list[$i]['data_coll_prop']));  //serialize();
                  }
                  if ($blocks_list[$i]['type']=='RichText') {
                      $filled_fields['Data__c'] = base64_encode( $blocks_list[$i]['model_prop']['Data'] );
                  }
              } else {
                  if ($w_field=='WidgetID') {
                        $filled_fields['Id'] = $blocks_list[$i]['model_prop'][$w_field];
                  } else {
                        $filled_fields[$w_field.'__c'] = $blocks_list[$i]['model_prop'][$w_field];
                  }
              }
            }
            $filled_fields['Type__c']    = $blocks_list[$i]['type'];
            $filled_fields['SlideId__c'] = $cur_slide_id;
            $records[$i]->fields         = $filled_fields;
            //error_log("\n filled fields: ".var_export($records[$i]->fields,true));


        }
        error_log("\n records: ".var_export($records,true));
      	$upsertResponse = $mySforceConnection->update($records);
       	error_log("<br>------SF answer:-------i=$i-----------------");
       	error_log("Response: ".var_export($upsertResponse,true));
    } catch (Exception $e){
          error_log("\n i=$i   w_field=$w_field  SF send>>>: ". $e->getMessage()." >>>:".$e->getCode()." trace>>>:".$e->getTrace());
          echo "{'error':$e->getMessage()}";
    }
}
?>