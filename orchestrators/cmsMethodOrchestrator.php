<?php
    require_once __DIR__."/../handlers/requestHandler.php";
    require_once __DIR__."/../handlers/responseHandler.php";
    require_once __DIR__."/../handlers/tokenHandler.php";
    require_once __DIR__."/../controllers/cmsController.php";

    class CMSMethodOrchestrator
    {
        private $cmsController;
        private $tokenHandler;

        public function __construct()
        {
            $database = new Database();
            $connection = $database->getConnection();
            $this->cmsController = new CMSController($connection);
            $this->tokenHandler = new TokenHandler();
        }

        public function Handle()
        {
            switch ($_SERVER['REQUEST_METHOD'])
            {
                case "GET":
                    $getRequestResponse = NewGetRequest("pageName");
                    if ($getRequestResponse->IsFail())
                        return $getRequestResponse;

                    return $this->cmsController->ReadPage($getRequestResponse->payload);
                break;
                case "PUT":
                    $tokenFromHeaderResponse = $this->tokenHandler->GetTokenFromHeader();
                    if ($tokenFromHeaderResponse->IsFail())
                        return $tokenFromHeaderResponse;
                    
                    if ($tokenFromHeaderResponse->payload->privilegeLevel < 2)
                        return NewAuthResponse(403, "Not the correct privilege level", $tokenFromHeaderResponse->payload);

                    $jsonRequestResponse = NewJsonObjectRequest(function ($p) {return PageData::Create($p);});
                    if ($jsonRequestResponse->IsFail())
                        return $jsonRequestResponse->WithToken($tokenFromHeaderResponse->payload);

                    return $this->cmsController->UpdatePage($jsonRequestResponse->payload)->WithToken($tokenFromHeaderResponse->payload);
                break;
                case "OPTIONS":
                    return NewResponse(200, "Options request response");
                break;
                default:
                    return NewResponse(400, "Invalid request method");
            }
        }
    }
?>