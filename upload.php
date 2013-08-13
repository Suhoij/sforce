<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload Document</title>
</head>

<body>
<?php

	include 'upload.class.php';
    // Upload(Field name of Form, File Type, upload directory)
	//$upload = new Upload('file', 'audio/mpeg', 'upload/' );
	//$upload = new Upload('file', 'application/pdf', 'output_html/' );
    $upload = new Upload('file', 'application/zip', 'upload/' );
    switch ($_GET['action']) {
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
            //$res=fwrite($handle, $data);
            $res=fwrite($handle, 'test');
            fclose($handle);
        echo "<br>Take done";
        break;
      default:
         $upload->uploaded();
    }
    /*
    if ($_GET['action']=='list') {
        $upload->show_files();
    } else {
        $upload->uploaded();
    }
    */
?>
</body>
</html>
