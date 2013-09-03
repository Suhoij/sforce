<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload ppt file</title>
</head>
 <?php //print_r(get_loaded_extensions()); ?>
<body>
    <h1>Convert PPT to HTML</h1>
    <p>
     <ul >
      <li >
       <a href="/upload.php?action=list">Uploaded files</a></li>
      <li ><a href="/output_html/">Converted files</a></li>
     </ul>
    </p>
    <h2>Select PowerPoint file </h2>
	<form action="upload.php" enctype="multipart/form-data" method="post">

        File:<input type="file" name="file" />
        <input type="submit" name="submit" value="Upload"  />

    </form>
<?php
   if (isset($_GET['convert'])) {
       echo "<br>convert start...";
       system("start cmd.exe ".getcwd()."/ruby_convertor.bat");
       echo "<br>convert done...";
   }
?>
</body>
</html>
