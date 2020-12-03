<?php
    header("Access-Control-Allow-Origin: *");
    require_once "orchestrators/userMethodOrchestrator.php";
    require_once "handlers/responseHandler.php";

    $userMethodOrchestrator = new UserMethodOrchestrator();
    $userMethodOrchestrator->Handle()->SendResponse();
?>