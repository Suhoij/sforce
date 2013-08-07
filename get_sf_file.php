<?php
	define("USERNAME", "e-detmobile@customertimes.com");
	define("PASSWORD", "poqw09123");
	define("SECURITY_TOKEN", "vYwfdDbmbtdJI7gRnlJZLwIe");
	//echo "11111111111111111<br>" ;
	require_once ('soapclient/SforcePartnerClient.php');
	//echo "222222222222222<br>" ;
	$mySforceConnection = new SforcePartnerClient();
	//echo "333333333333<br>" ;
	$mySforceConnection->createConnection("partner.wsdl.xml");
	//echo "4444444<br>" ;
	$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
	
	//$query = "SELECT Id, FirstName, LastName, Phone from Contact";
	$query = "SELECT Id,Name,BodyLength FROM Attachment WHERE Id='00PG0000007rWHq'";
	$response = $mySforceConnection->query($query);
var_dump($response );

	echo "Results of query '$query'<br/><br/>\n";
	foreach ($response->records as $record) {
	    // Id is on the $record, but other fields are accessed via the fields object
	   //var_dump($record);
	   echo "<hr>";
	   echo "<br> Body length:".$record->BodyLength[0]."<br>";
	   echo "Id:".$record->Id[0] . "<br> name: " .  base64_decode($record->any) . "<br/>\n";
	
	}

//$file = $response->records[0]->Body;
//$name = $response->records[0]->Name;
//$length = $response->records[0]->BodyLength;
/*
$my_file="YES_".$response[0]->Name;
$data = $response[0]->Body;

$handle = fopen($my_file, 'w')
fwrite($handle, $data);
fclose($handle);
*/
echo "<hr>";
//echo "Id:".$response->records[0]->Id . " name: " . $response->records[0]->Name. "<br/>\n";

//header('Content-Type: application/force-download');
//header('Content-Disposition: inline; filename="'.$name.'"');
//header('Content-Length: '.$length);


//if($file) echo $file;
?>