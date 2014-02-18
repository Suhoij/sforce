<?php
//*** tokens control ***
//
//define("PATH_AZURE_PHP", "services/azure-sdk-for-php/");
define("PATH_AZURE_PHP", "pear/WindowsAzure/");

//require_once PATH_AZURE_PHP."WindowsAzure/WindowsAzure.php";
require_once PATH_AZURE_PHP."WindowsAzure.php";
//require_once "WindowsAzure/WindowsAzure.php";
use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Table\Models\Entity;
use WindowsAzure\Table\Models\EdmType;


$name='sfclmstorage';
$key='JtyoTzjNZHBAWVuDQNpN3LaJcPWjc51bMvRT7xG4hZA7vpT6qZTjKoJUU6z8sY2+zsvsGe7j05OupWmumABz5A==';
//$UseDevelopmentStorage=true
$connectionString  = "DefaultEndpointsProtocol=http;AccountName=$name;AccountKey=$key";
echo "Start azure";


$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);

$filter = "org_id eq '00D20000000Caz4EAC'";

try {
    $result = $tableRestProxy->queryEntities("sftokens", $filter);
} catch(ServiceException $e){
    $code = $e->getCode();
    $error_message = $e->getMessage();
    echo $code.": ".$error_message."<br />";
}

$entities = $result->getEntities();
var_dump($entities);
foreach($entities as $entity){
    echo $entity->getPartitionKey().":".$entity->getRowKey()."<br />";
}

?>