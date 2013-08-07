<?php
class ListFiles {

public function show() {
// open this directory 
//$myDirectory = opendir('c:/PPT-HTML/input_ppt/');//--\\Ppt-to-html\ppt-html  c:/PPT-HTML/ c:/PPT-HTML/output_html/
$myDirectory = opendir('input_ppt/');
//print_r(getcwd());
// get each entry
while($entryName = readdir($myDirectory)) {
	$dirArray[] = $entryName;
}

// close directory
closedir($myDirectory);

//	count elements in array
$indexCount	= count($dirArray);
Print ("$indexCount files<br>\n");

// sort 'em
sort($dirArray);

// print 'em
print("<TABLE border=1 cellpadding=5 cellspacing=0 class=whitelinks>\n");
print("<TR><TH>Filename</TH><th>Filetype</th><th>Filesize</th></TR>\n");
// loop through the array of files and print them all
for($index=0; $index < $indexCount; $index++) {
        if (substr("$dirArray[$index]", 0, 1) != "."){ // don't list hidden files
		print("<TR><TD><a href=\"$dirArray[$index]\">$dirArray[$index]</a></td>");
		print("<td>");
		print(filetype($dirArray[$index]));
		print("</td>");
		print("<td>");
		print(filesize($dirArray[$index]));
		print("</td>");
		print("</TR>\n");
	}
}
print("</TABLE>\n");
}
}
//---------main----------
if ($_GET['action']=="convert") {
	echo "<br>start:".getcwd();
	$cmd_str="c:\PPT-HTML\\files_for_redistribution\ppt2html5.exe /i:'c:\PPT-HTML\input_ppt\\file.pptx'  /o:'c:\PPT-HTML\output_html\\file.html'";
	$cmd_str="C:\\inetpub\\wwwroot\\files_for_redistribution\\ppt2html5.exe /i:C:\\inetpub\\wwwroot\\input_ppt\\file.pptx  /o:C:\\inetpub\\wwwroot\\output_html\\file.html";
	$cmd_str="c:\\inetpub\wwwroot\\ruby_convertor.bat";
	//$cmd_str=getcwd()."\PPT-HTML\\files_for_redistribution\ppt2html5.exe /i:'".getcwd()."\PPT-HTML\input_ppt\\file.pptx'  /o:'".getcwd()."\PPT-HTML\output_html\\file.html'";
	//$cmd_str="dir c:/PPT-HTML >>c:/PPT-HTML/a.txt";
	//$cmd_str="dir c:/PPT-HTML >>c:/PPT-HTML/a.txt";
	//echo exec("c:/PPT-HTML/files_for_redistribution/ppt2html5.exe /i:'c:/PPT-HTML/input_ppt/file.pptx'  /o:'c:/PPT-HTML/output_html/file.html'");
	echo "<br>".$cmd_str;
	echo exec($cmd_str);
        //$output = shell_exec("c:/PPT-HTML/cmd_dir.bat"); 
	//exec("cmd.exe /c c:/PPT-HTML/cmd_dir.bat");
$f=fopen('C:/inetpub/wwwroot/output_html/f.txt','w');fwrite($f,"aaaaaa");fclose($f);
/*
echo "<pre>$output</pre>"; 
	$last_line = system($cmd_str, $retval);
	//Printing additional info
	echo '
	</pre>
	<hr />Last line of the output: ' . $last_line . '
	<hr />Return value: ' . $retval;
*/
	echo "<br>Done!";
} else {
	$sf = new ListFiles;
	$sf->show();
}
?>