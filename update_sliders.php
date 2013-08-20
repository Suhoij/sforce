<?php
   //echo '<br>sf_ext included<br>';


      include "sf_ext.php";
      //echo "<br>No dir ".$f_id;
      $sf=new SfExt();
      //echo "<br> sf init";
      //$sf->takeFile($f_id);
      //echo "<br>sf - :state=$sf->state";
      if (isset($_GET['f_name'])) {
          $sf->f_name=$_GET['f_name'];
      } else {
          $sf->f_name='index.html';
      }
      if (isset($_GET['f_id'])) {
	      //$this->f_id=$_GET['f_id'];
          $sf->takeFile($sf->f_id);
          $sf->saveFile();
     	  $sf->createSliderHTML();
      } else {
          $sf->takeSlidersId();
          $sf->findAbsentSliders();
          $sf->createAbsentSliders();
      }
      //$sf->saveFile();
      //$sf->createSliderHTML();
      //echo $sf->state;
   


?>