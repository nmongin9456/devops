<?php

define("VIM25_NAMESPACE", "urn:vim25");
define("VIM25_SUB_INFO_ALL", "all");
define("VIM25_SUB_INFO_GUEST", "guest");
require "class/nusoap/nusoap.php";

class vim25
{
	protected $client = null;
	
	public function __construct($vc_ip, $username, $password)
	{
		//$this->client = new soapclient("https://{$vc_ip}/sdk");
		
		$this->client = new SoapClient("https://{$vc_ip}/sdk/vimService.wsdl", 
			array(
				"trace" => 1, 
				"exception" => 1, 
				"location" =>"https://{$vc_ip}/sdk/",
				"stream_context" => stream_context_create(
					array(
						"ssl" => array(
							"verify_peer" => false,
							"verify_peer_name" => false
							)
						)
					)
			));
		$this->login($username, $password);
	}
	
	public function getSOAPClient()
	{
		return $this->client;
	}
	
	public function login($username, $password)
	{
		$params = array(
			"_this" => new soapval('_this', false, "SessionManager", false, false, array("type" => "SessionManager")),
			"userName" => $username,
			"password" => $password
		);
		print_r("<pre>");
		print_r($params);
		return $this->getSOAPClient()->call("Login", $params, VIM25_NAMESPACE);
	}
	
	public function retrieveServiceContent()
	{
		$params = array(
			"data" => new soapval("_this", "ServiceInstance", "ServiceInstance")
		);
		return $this->getSOAPClient()->call("RetrieveServiceContent", $params, VIM25_NAMESPACE);
	}
	
	public function getAllVMInfo($root_folder, $sub_info)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Body>';

		$xml .= '<RetrieveProperties xmlns="' . VIM25_NAMESPACE . '">';
		$xml .= '<_this type="PropertyCollector">propertyCollector</_this>';
		$xml .= '<specSet>';
		$xml .= ' <propSet>';
		$xml .= '  <type>VirtualMachine</type>';
		$xml .= ($sub_info == VIM25_SUB_INFO_ALL) ? '  <all>true</all>' : "  <pathSet>$sub_info</pathSet>";
		$xml .= ' </propSet>';
		$xml .= ' <objectSet>';
		$xml .= ' <obj type="Folder">' . $root_folder . '</obj>';
		$xml .= '  <selectSet xsi:type="TraversalSpec">';
		$xml .= '   <name>traverseChild</name>';
		$xml .= '   <type>Folder</type>';
		$xml .= '   <path>childEntity</path>';
		$xml .= '   <selectSet><name>traverseChild</name></selectSet>';
		$xml .= '   <selectSet xsi:type="TraversalSpec">';
		$xml .= '    <type>Datacenter</type>';
		$xml .= '    <path>vmFolder</path>';
		$xml .= '    <selectSet><name>traverseChild</name></selectSet>';
		$xml .= '    </selectSet>';
		$xml .= '  </selectSet>';
		$xml .= ' </objectSet>';
		$xml .= '</specSet>';
		$xml .= '</RetrieveProperties>';
		$xml .= '</SOAP-ENV:Body></SOAP-ENV:Envelope>';

		return $this->getSOAPClient()->send($xml);
	}
	
	public function getVMInfo($vm_id, $sub_info)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Body>';

		$xml .= '<RetrieveProperties xmlns="' . VIM25_NAMESPACE . '">';
		$xml .= '<_this type="PropertyCollector">propertyCollector</_this>';
		$xml .= '<specSet>';
		$xml .= ' <propSet>';
		$xml .= '  <type>VirtualMachine</type>';
		$xml .= ($sub_info == VIM25_SUB_INFO_ALL) ? '  <all>true</all>' : "  <pathSet>$sub_info</pathSet>";
		$xml .= ' </propSet>';
		$xml .= ' <objectSet>';
		$xml .= ' <obj type="VirtualMachine">' . $vm_id . '</obj>';
		$xml .= '  <selectSet xsi:type="TraversalSpec">';
		$xml .= '   <name>traverseChild</name>';
		$xml .= '   <type>Folder</type>';
		$xml .= '   <path>childEntity</path>';
		$xml .= '   <selectSet><name>traverseChild</name></selectSet>';
		$xml .= '   <selectSet xsi:type="TraversalSpec">';
		$xml .= '    <type>Datacenter</type>';
		$xml .= '    <path>vmFolder</path>';
		$xml .= '    <selectSet><name>traverseChild</name></selectSet>';
		$xml .= '    </selectSet>';
		$xml .= '  </selectSet>';
		$xml .= ' </objectSet>';
		$xml .= '</specSet>';
		$xml .= '</RetrieveProperties>';
		$xml .= '</SOAP-ENV:Body></SOAP-ENV:Envelope>';

		return $this->getSOAPClient()->send($xml);
	}
	
	public function createVM($name, $folder, $guest_id, $resource_pool, $host, $num_cpu, $memory_mb, $disk_capacity_kb)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Body>';
		
		$xml .= '<CreateVM_Task xmlns="' . VIM25_NAMESPACE . '">';
		$xml .= '<_this type="Folder">' . $folder . '</_this>';
		$xml .= '<config>';
		$xml .= ' <name>' . $name . '</name>';
		$xml .= ' <guestId>' . $guest_id . '</guestId>';
		$xml .= ' <files>';
		$xml .= '  <vmPathName>[esx-host-32:storage1]</vmPathName>';
		$xml .= ' </files>';
		$xml .= ' <numCPUs>' . $num_cpu . '</numCPUs>';
		$xml .= ' <memoryMB>' . $memory_mb . '</memoryMB>';
		$xml .= ' <deviceChange>';
		$xml .= '  <operation>add</operation>';
		$xml .= '  <device xsi:type="VirtualBusLogicController">';
		$xml .= '   <key>0</key>';
		$xml .= '   <busNumber>0</busNumber>';
		$xml .= '   <device>0</device>';
		$xml .= '   <sharedBus>noSharing</sharedBus>';
		$xml .= '  </device>';
		$xml .= ' </deviceChange>';
		$xml .= ' <deviceChange>';
		$xml .= '  <operation>add</operation>';
		$xml .= '  <fileOperation>create</fileOperation>';
		$xml .= '  <device xsi:type="VirtualDisk">';
		$xml .= '   <key>0</key>';
		$xml .= '   <backing xsi:type="VirtualDiskFlatVer2BackingInfo">';
		$xml .= '    <fileName>[esx-host-32:storage1]</fileName>';
		$xml .= '    <diskMode>persistent</diskMode>';
		$xml .= '   </backing>';
		$xml .= '   <controllerKey>0</controllerKey>';
		$xml .= '   <unitNumber>0</unitNumber>';
		$xml .= '   <capacityInKB>' . $disk_capacity_kb . '</capacityInKB>';
		$xml .= '  </device>';
		$xml .= ' </deviceChange>';
		$xml .= '</config>';
		$xml .= '<pool type="ResourcePool">' . $resource_pool . '</pool>';
		$xml .= '<host type="HostSystem">' . $host . '</host>';
		$xml .= '</CreateVM_Task>';
		$xml .= '</SOAP-ENV:Body></SOAP-ENV:Envelope>';
		
		$return = $this->getSOAPClient()->send($xml);
		
		if (isset($return["returnval"])) {
			$task_id = $return["returnval"];
			$return = $this->waitForTask($task_id);
		}
		
		return $return;
	}
	
	public function waitForTask($id)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Body>';
		
		$xml .= '<RetrieveProperties xmlns="' . VIM25_NAMESPACE . '">';
		$xml .= '<_this type="PropertyCollector">propertyCollector</_this>';
		$xml .= '<specSet>';
		$xml .= ' <propSet>';
		$xml .= '  <type>Task</type>';
		$xml .= '  <all>1</all>';
		$xml .= ' </propSet>';
		$xml .= ' <objectSet>';
		$xml .= '  <obj type="Task">' . $id . '</obj>';
		$xml .= ' </objectSet>';
		$xml .= '</specSet>';
		$xml .= '</RetrieveProperties>';
		$xml .= '</SOAP-ENV:Body></SOAP-ENV:Envelope>';
		
		do {
			sleep(1);
			$return = $this->getSOAPClient()->send($xml);
			$return = $return["returnval"]["propSet"][1]["val"];
		} while ($return["state"] == "running");
		
		return $return;
	}
}
