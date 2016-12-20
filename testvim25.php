<?php    
    require "vim25.php";
    $vim25 = new vim25("55.171.1.48", "cnamts\c417501-vcro", "Serp!l2016");

    $service_content = $vim25->retrieveServiceContent();

    $all_vm_info = $vim25->getAllVMInfo($service_content["rootFolder"], VIM25_SUB_INFO_ALL);
    $vm_info = $vim25->getVMInfo("vm-175236", VIM25_SUB_INFO_GUEST);

/*
    $debug = $vim25->createVM(
    "vm name",
    "group-id",
    "guest-id",
    "resgroup-id",
    "host-id",
    2, // num CPU
    512, // RAM
    10485760 // disk capacity in kB (10GB)
    );

    print_r($debug);
*/