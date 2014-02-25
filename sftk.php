<?php
//*** tokens control ***
//
$dev='ppthtml2';
if (gethostname()==$dev) {
   define("PATH_AZURE_PHP", "pear/WindowsAzure/");
   //define("PATH_AZURE_PHP", "services/azure-sdk-for-php/");
} else {
   define("PATH_AZURE_PHP", "pear/WindowsAzure/");

}


//require_once PATH_AZURE_PHP."WindowsAzure/WindowsAzure.php";
require_once PATH_AZURE_PHP."WindowsAzure.php";
//require_once "WindowsAzure/WindowsAzure.php";
use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Table\Models\Entity;
use WindowsAzure\Table\Models\EdmType;
 /*

$name='sfclmstorage';
$key='JtyoTzjNZHBAWVuDQNpN3LaJcPWjc51bMvRT7xG4hZA7vpT6qZTjKoJUU6z8sY2+zsvsGe7j05OupWmumABz5A==';
//$UseDevelopmentStorage=true
$connectionString  = "DefaultEndpointsProtocol=http;AccountName=$name;AccountKey=$key";
echo "Start azure";


$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);

//$filter = "org_id eq '00D20000000Caz4EAC'";
$filter = "token eq '88e3a10944d71ac5fe8739ed'";
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
*/
//-------------------------Azure storage class------------------------------------
class AzureStore {
  var $state;
  var $az_table_proxy;
  var $az_table_tokens;
  var $az_table_name;
  var $org_id='';
  var $app_id='';
  function __construct() {
     $name='sfclmstorage';
     $key='JtyoTzjNZHBAWVuDQNpN3LaJcPWjc51bMvRT7xG4hZA7vpT6qZTjKoJUU6z8sY2+zsvsGe7j05OupWmumABz5A==';
     $connectionString  = "DefaultEndpointsProtocol=http;AccountName=$name;AccountKey=$key";
     $this->az_table_tokens="sftokens";
     try {
        $this->az_table_proxy = ServicesBuilder::getInstance()->createTableService($connectionString);
        $this->state.=';conected';
     } catch(ServiceException $e){
        $code = $e->getCode();
        $error_message = $e->getMessage();
        $this->state.=';error-conection';
        error_log(__FUNCTION__.' '.$this->state."  ".$code.$error_message);
     }
  }
  function addToken($org_id,$app_id) {
        $entity = new Entity();
        $entity->setPartitionKey($org_id);
        $entity->setRowKey($app_id);
        $cur_token=uniqid();
        $entity->addProperty("token", null, $cur_token);
        //$entity->addProperty("DueDate",EdmType::DATETIME,new DateTime("2012-11-05T08:15:00-08:00"));
        try {
            $this->az_table_proxy->insertEntity($this->az_table_tokens, $entity);
            return $cur_token;
        } catch (ServiceException $e) {
            error_log(__FUNCTION__.' '. $e->getCode().' '.$e->getMessage());
            return 0;
        }
  }
  function getToken($org_id,$app_id) {
        $filter = "PartitionKey eq '$org_id' and RowKey eq '$app_id'";
        $cur_token='0';
        try {
            $result = $this->az_table_proxy->queryEntities($this->az_table_tokens, $filter);
            $entities = $result->getEntities();
            $cur_token=$entities[0]->getProperty("token")->getValue();
        }  catch(ServiceException $e){
            $cur_token ='-1';
            error_log(__FUNCTION__.' '. $e->getCode().' '.$e->getMessage());
        }
        return $cur_token;
  }
  function findByToken($tk) {
       $filter = "token eq '$tk'";
       try {
          //echo "findByKey <br>filter=$filter";
          $result = $this->az_table_proxy->queryEntities($this->az_table_tokens, $filter);
          $entities = $result->getEntities();
          //var_dump($entities);
          $this->state.=';table-conected';
          if (count($entities) >=1 ) {
              $this->org_id=$entities[0]->getPartitionKey();//$entities[0]->getProperty("token")->getValue();
              $this->app_id=$entities[0]->getRowKey();
              return true;
          } else return false;
      } catch(ServiceException $e){
          $code = $e->getCode();
          $error_message = $e->getMessage();
          $this->state.='error-conection-table';
          error_log(__FUNCTION__.' '.$this->state."  ".$code.$error_message);
          return false;
      }
  }
  function findByOrgApp($org_id,$app_id) {
         $filter = "PartitionKey eq '$org_id' and RowKey eq '$app_id'";
         try {
            $result = $this->az_table_proxy->queryEntities($this->az_table_tokens, $filter);
            $entities = $result->getEntities();
            if (count($entities)>=1){
                $this->org_id=$org_id;
                $this->app_id=$app_id;
            }
            return true;
         } catch(ServiceException $e){
            $code = $e->getCode();
            $error_message = $e->getMessage();
            $this->state.='error-conection-table-by-org-app';
            error_log(__FUNCTION__.' '.$this->state."  ".$code.$error_message);
            return false;
         }
  }
}
?>