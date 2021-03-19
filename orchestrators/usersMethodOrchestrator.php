<?php
    require_once __DIR__."/../handlers/requestHandler.php";
    require_once __DIR__."/../handlers/responseHandler.php";
    require_once __DIR__."/../handlers/tokenHandler.php";
    require_once __DIR__."/../controllers/usersController.php";

    class UsersMethodOrchestrator
    {
        private $usersController;
        private $tokenHandler;
        public function __construct()
        {
            $database = new Database();
            $connection = $database->getConnection();
            $this->usersController = new UsersController($connection);
            $this->tokenHandler = new TokenHandler();
        }

        public function Handle()
        {
            if (!IsOptionsRequest()->IsFail())
                return IsOptionsRequest();
                
            $tokenFromHeaderResponse = $this->tokenHandler->GetTokenFromHeader();
            if ($tokenFromHeaderResponse->IsFail())
                return $tokenFromHeaderResponse;

            if ($tokenFromHeaderResponse->payload->privilegeLevel < 3)
                return NewAuthResponse(403, "Not the correct privilege level", $tokenFromHeaderResponse->payload);

            switch($_SERVER['REQUEST_METHOD'])
            {
                case "GET":
                    $jsonRequestResponse = NewGetRequest("username");
                    if ($jsonRequestResponse->IsFail())
                        return $this->usersController->ReadUsers()->WithToken($tokenFromHeaderResponse->payload);
                    return $this->usersController->ReadUser($jsonRequestResponse->payload)->WithToken($tokenFromHeaderResponse->payload);
                break;
                case "PUT":
                    $jsonRequestResponse = NewJsonObjectRequest(function ($p) { return User::CreateWithoutPass($p);});
                    if ($jsonRequestResponse->IsFail())
                        return NewAuthResponse(400, "Invalid user", $tokenFromHeaderResponse->payload);
                    return $this->usersController->UpdateUser($jsonRequestResponse->payload)->WithToken($tokenFromHeaderResponse->payload);
                break;
                case "POST":
                    $jsonRequestResponse = NewJsonObjectRequest(function ($p) { return User::Create($p);});
                    if ($jsonRequestResponse->IsFail())
                        return NewResponse(400, "Invalid user", $tokenFromHeaderResponse->payload);
                    return $this->usersController->CreateUser($jsonRequestResponse->payload)->WithToken($tokenFromHeaderResponse->payload);
                break;
                case "DELETE":
                    $jsonRequestResponse = NewJsonParamRequest("username");
                    if ($jsonRequestResponse->IsFail())
                        return $jsonRequestResponse->WithToken($tokenFromHeaderResponse->payload);
                    return $this->usersController->DeleteUser($jsonRequestResponse->payload)->WithToken($tokenFromHeaderResponse->payload);
                break;
                default:
                    return NewAuthResponse(400, "Unknown request", $tokenFromHeaderResponse->payload);
            }
        }
    }
?>