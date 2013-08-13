<?php   
   //echo '<br>sf_ext included<br>';
   
      if (isset($_GET[$f_id])) {
	$f_id=$_GET['f_id'];
     	//echo "yes";
      } 
      include "sf_ext.php";
      //echo "<br>No dir ".$f_id;
      $sf=new SfExt();
      //echo "<br> sf init";
      $dir = glob(getcwd() . '/output_html/sliders_html/*' , GLOB_ONLYDIR);
      //print_r($dir);
      $sliders_id_cloud=array();
      foreach ($dir as $item) {
         $s_arr = explode('/',$item);
         $s_id  = $s_arr[count($s_arr)-1];
         //print_r("<br>".$s_arr[count($s_arr)-1]);
         array_push($sliders_id_cloud,$s_id);
       }
      //var_dump($sliders_id_arr);

      //$sf->takeFile($f_id);
      echo '<br>sf - :state=$sf->state';
      if (isset($_GET['f_name'])) {
          $sf->f_name=$_GET['f_name'];
      } else {
          $sf->f_name='index.html';
      }
      $sf->takeSlidersId();
      //$sf->findAbsentSliders();
      //$sf->createAbsentSliders();

      //$sf->saveFile();
      //$sf->createSliderHTML();
      echo $sf->state;
   


?>