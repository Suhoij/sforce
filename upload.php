<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload Document</title>
</head>

<body>
<?php

	include 'upload.class.php';
    $upload = new Upload('file', 'application/zip', 'upload/' );
    $_REQUEST['action']
    if (isset( $_REQUEST['action'] )) {
          switch ( $_REQUEST['action'] ) {
            case 'delete-slides':
              $upload->deleteSlides();
              break;
            case 'list':
              $upload->show_files();
              break;
            case 'convert':
              include 'sf_ext.php';
              echo "<br> include";
              $sf=new SfExt();
              echo "<br> new";
              $sf->takeFile($_GET['f_id']);
              $f_full_name=getcwd()."\\upload\\$f_name";
              $handle = fopen($f_full_name, 'w');
              $res=fwrite($handle, 'test');
              fclose($handle);
              echo "<br>Take done";
              break;
            case 'send':
               $upload->uploaded();
               break;
            default:
               $upload->uploaded();
          }
    } else {
          $upload->uploaded();
    }

?>
</body>
</html>
