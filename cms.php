<?php
    require_once "orchestrators/cmsMethodOrchestrator.php";
    require_once "handlers/responseHandler.php";

    $cmsMethodOrchestrator = new CMSMethodOrchestrator();
    $cmsMethodOrchestrator->Handle()->SendResponse();
?>