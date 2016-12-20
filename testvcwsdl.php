<?php

require 'inc/bootstrap.php';

// Update $client to reflect your own IP or DNS name instead of 'vcenter'
// Update $soap_message["userName"]
// Update $soap_message["password"]

// Need to declare these settings here because our php.ini has alternate
// settings due to global purposes for other PHP scripts
ini_set("soap.wsdl_cache_enabled", "0");
ini_set("soap.wsdl_cache", "0");
ini_set("display_errors","On");
ini_set("track_errors","On");

echo "<pre>";

$client = new SoapClient('https://55.171.1.48/sdk/vimService.wsdl', 
	array(
		"trace" => 1, 
		"exception" => 1, 
		"location" =>"https://55.171.1.48/sdk/",
		"stream_context" => stream_context_create(
			array(
				"ssl" => array(
					"verify_peer" => false,
					"verify_peer_name" => false
					)
				)
			)
	));

//print_r($client->__getFunctions());

$request = new stdClass();
$request->_this = array ('_' => 'ServiceInstance', 'type' => 'ServiceInstance');

try {
	$response = $client->__soapCall('RetrieveServiceContent', array((array)$request));
} catch (Exception $e){
    echo $e->getMessage();
    exit;
}
$ret = $response->returnval;
//print_r($response);


try
{
    $request = new stdClass();
    $request->_this = $ret->sessionManager;
    $request->userName = 'cnamts\c417501-tachad';
    $request->password = 'Ie1fdlouest';
    $response = $client->__soapCall('Login', array((array)$request));
} catch (Exception $e)
{
    echo $e->getMessage();
    exit;
}
//print_r($ret);
//print_r("--------------------------------------------------------------------------------");
//print_r($response);

$ss1 = new soapvar(array ('name' => 'FolderTraversalSpec'), SOAP_ENC_OBJECT, null, null, 'selectSet', null);
$ss2 = new soapvar(array ('name' => 'DataCenterVMTraversalSpec'), SOAP_ENC_OBJECT, null, null, 'selectSet', null);
$a = array ('name' => 'FolderTraversalSpec', 'type' => 'Folder', 'path' => 'childEntity', 'skip' => false, $ss1, $ss2);
$ss = new soapvar(array ('name' => 'FolderTraversalSpec'), SOAP_ENC_OBJECT, null, null, 'selectSet', null);
$b = array ('name' => 'DataCenterVMTraversalSpec', 'type' => 'Datacenter', 'path' => 'vmFolder', 'skip' => false, $ss);
$res = null;

print_r($ss1);
print_r("<br>");
print_r($ss2);
print_r("<br>");
print_r($a);
print_r("<br>");
print_r($b);
print_r("<br>");
print_r("***************************************************");
try
{
    $request = new stdClass();
    $request->_this = $ret->propertyCollector;

    /* Set de properties....
    $request->specSet = array (
        'propSet' => array (
            array ('type' => 'VirtualMachine', 'all' => 0, 'pathSet' => array ('name', 'guest.ipAddress', 'guest.guestState', 'runtime.powerState', 'config.hardware.numCPU', 'config.hardware.memoryMB')),
        ),
        'objectSet' => array (
            'obj' => $ret->rootFolder,
            'skip' => false,
            'selectSet' => array (
                new soapvar($a, SOAP_ENC_OBJECT, 'TraversalSpec'),
                new soapvar($b, SOAP_ENC_OBJECT, 'TraversalSpec'),
                ),
            )
        );*/

    //Toutes les propriétés 
    $request->specSet = array (
        'propSet' => array (
            array ('type' => 'VirtualMachine', 'all' => 1),
        ),
        'objectSet' => array (
            'obj' => $ret->rootFolder,
            'skip' => false,
            'selectSet' => array (
                new soapvar($a, SOAP_ENC_OBJECT, 'TraversalSpec'),
                new soapvar($b, SOAP_ENC_OBJECT, 'TraversalSpec'),
                ),
            )
        );
        /*
    $request->specSet = array (
        'propSet' => array (
            array ('type' => 'VirtualMachine', 'all' => 0, 'pathSet' => array('name', 'runtime.powerState')),
        ),
        'objectSet' => array (
            'obj' => $ret->rootFolder,
            'skip' => false,
            'selectSet' => array (
                new soapvar($a, SOAP_ENC_OBJECT, 'TraversalSpec'),
                new soapvar($b, SOAP_ENC_OBJECT, 'TraversalSpec'),
                ),
            )
        );*/
    $res = $client->__soapCall('RetrieveProperties', array((array)$request));
} catch (Exception $e)
{
    echo $e->getMessage();
}
$results = [];
foreach($res->returnval as $k => $vm){
	$properties = $vm->propSet;
	$tabP = [];
	foreach($properties as $property){
		$tabP[$property->name] = $property->val;
	}
	$results[$tabP['name']] = $tabP;
}
//print_r($results);

//$request2 = new stdClass();
//$request2->_this = $ret->guestOperationsManager;
//print_r($request2);

/*
$ss1 = new soapvar(array ('name' => 'FolderTraversalSpec'), SOAP_ENC_OBJECT, null, null, 'selectSet', null);
$soapmsg['this']=new soapvar(array("type" => "SearchIndex"), SOAP_ENC_OBJECT, null, null,'SearchIndex', null);
$soapmsg['this']=new soapvar('_this', false, 'SearchIndex', false, false, array("type" => "SearchIndex"));
$soapmsg['ip']="55.171.8.111";
$soapmsg['vmSearch'] = "true";
$results=$client->__soapCall("FindByIp", $soapmsg);

debug($results);
*/


echo "</pre>";
