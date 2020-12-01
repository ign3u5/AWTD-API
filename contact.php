<?php
    require_once "orchestrators/contactMethodOrchestrator.php";
    require_once "handlers/responseHandler.php";

    $contactMethodOrchestrator = new ContactMethodOrchestrator();
    $contactMethodOrchestrator->Handle()->SendResponse();
?>