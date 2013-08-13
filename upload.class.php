<?php
/////////////// Upload FILE CLASS ////////////////
//***********************************************
//************************************************
//************** CREATED BY RK *******************
//************ Owner of Tracepk.net **************
///////////////////////////////////////////////////
class Upload {
  var $fieldname;
  var $type;
  var $upload_dir;
  var $filename;
  function __construct($n_fieldname, $n_type, $n_upload_dir) {
    $this->fieldname = $n_fieldname;
    $this->type = $n_type;
    $this->type_arr = array('application/ppt', 'application/pptx', 'application/vnd.ms-powerpoint');
    $this->upload_dir = $n_upload_dir;
    //$this->filename = $n_filename;
    //$this->uploaded();
  }
  function show_files() {
    $myDirectory = opendir($this->upload_dir);
    //print_r(getcwd());
    // get each entry
    while ($entryName = readdir($myDirectory)) {
      $dirArray[] = $entryName;
    }
    // close directory
    closedir($myDirectory);
    //	count elements in array
    $indexCount = count($dirArray);
    //print ("<a href='http://ppt-to-html.cloudapp.net'>Home</a>");
    print ("<a href='http://ppthtml2.cloudapp.net'>Home</a>");
    print ('<h1>Uploaded files list</h1>');
    Print ("$indexCount-2 files<br>\n");
    // sort 'em
    sort($dirArray);
    // print 'em
    print ("<TABLE border=1 cellpadding=5 cellspacing=0 class=whitelinks>\n");
    print ("<TR><TH>Filename</TH><th>FileExt</th><th>Filesize</th></TR>\n");
    // loop through the array of files and print them all
    for ($index = 0; $index < $indexCount; $index++) {
      if (substr("$dirArray[$index]", 0, 1) != ".") { // don't list hidden files
        print ("<TR><TD><a href=\"$this->upload_dir$dirArray[$index]\">$dirArray[$index]</a></td>");
        print ("<td>");
        //print (filetype(getcwd()."/".$dirArray[$index]));
        print (pathinfo(getcwd()."/".$this->upload_dir.$dirArray[$index], PATHINFO_EXTENSION));
        print ("</td>");
        print ("<td>");
        print ((filesize(getcwd()."/".$this->upload_dir.$dirArray[$index])/1000)." kb");
        //print (getcwd()."/".$this->upload_dir.$dirArray[$index]);
        print ("</td>");
        print ("</TR>\n");
      }
    }
    print ("</TABLE>\n");
  }


function uploaded() {
//if( $_FILES[$this->fieldname]["type"] == $this->type ){
//if (in_array($_FILES[$this->fieldname]["type"], $this->type_arr )) {
var_dump($_GET);
var_dump($_POST);
var_dump($_FILES);
  if (1 == 1) {
//--check the same name---
    if ($_FILES[$this->fieldname]["error"] == 0) {
      echo "<b>File name: </b>" . $_FILES[$this->fieldname]["name"] . "<br />";
      echo "<b>File type: </b>" . $_FILES[$this->fieldname]["type"] . "<br />";
      echo "<b>File size: </b>" . (($_FILES[$this->fieldname]["size"] / 1024) / 1024) . " Mb<br />";
//echo "<b>File Tmp: </b>" . $_FILES[$this->fieldname]["tmp_name"] . "<br />";
      if (move_uploaded_file($_FILES[$this->fieldname]["tmp_name"], $this->upload_dir . $_FILES[$this->fieldname]["name"])) {
        echo "Uploaded..";
      }
    }
    else {
      echo "Error: " . $_FILES[$this->fieldname]["error"] . "<br />";
    }
  }
  else {
    echo "Wrong file type..";
  }
  $this->show_files();
}
 }
?>
