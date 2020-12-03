<?php
    require_once "orchestrators/userMethodOrchestrator.php";
    require_once "handlers/responseHandler.php";
    header('Access-Control-Allow-Origin: *');

    $userMethodOrchestrator = new UserMethodOrchestrator();
    $userMethodOrchestrator->Handle()->SendResponse();
?>