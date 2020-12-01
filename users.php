<?php
    require_once "orchestrators/usersMethodOrchestrator.php";
    require_once "handlers/responseHandler.php";

    $usersMethodOrchestrator = new UsersMethodOrchestrator();
    $usersMethodOrchestrator->Handle()->SendResponse();
?>