<?php
    header("Access-Control-Allow-Origin: https://ws311471.remote.ac");   
    header("Content-Type: application/json; charset=UTF-8");    
    header("Access-Control-Allow-Methods: POST, OPTIONS");    
    header("Access-Control-Max-Age: 3600");    
    header("Access-Control-Allow-Headers: Content-Type, content-type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Token");  
    header("Access-Control-Expose-Headers: Token");
    require_once "orchestrators/contactMethodOrchestrator.php";
    require_once "handlers/responseHandler.php";

    $contactMethodOrchestrator = new ContactMethodOrchestrator();
    $contactMethodOrchestrator->Handle()->SendResponse();
?>