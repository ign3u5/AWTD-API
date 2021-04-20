<?php
    header("Access-Control-Allow-Origin: https://ws311471.remote.ac");   
    header("Content-Type: application/json; charset=UTF-8");    
    header("Access-Control-Allow-Methods: GET, PUT, OPTIONS");    
    header("Access-Control-Max-Age: 3600");    
    header("Access-Control-Allow-Headers: Content-Type, content-type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Token");  
    header("Access-Control-Expose-Headers: Token");
    require_once "orchestrators/cmsMethodOrchestrator.php";
    require_once "handlers/responseHandler.php";

    $cmsMethodOrchestrator = new CMSMethodOrchestrator();
    $cmsMethodOrchestrator->Handle()->SendResponse();
?>