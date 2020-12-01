<?php
    require_once __DIR__."/../handlers/requestHandler.php";
    require_once __DIR__."/../handlers/responseHandler.php";
    require_once __DIR__."/../handlers/tokenHandler.php";
    require_once __DIR__."/../controllers/usersController.php";
    
    class UserMethodOrchestrator
    {
        private $userController;
        private $tokenHandler;
        public function __construct()
        {
            $database = new Database();
            $connection = $database->getConnection();
            $this->userController = new UsersController($connection);
            $this->tokenHandler = new TokenHandler();

        }
        public function Handle()
        {
            switch ($_SERVER['REQUEST_METHOD'])
            {
                case "GET":
                    $tokenFromHeaderResponse = $this->tokenHandler->GetTokenFromHeader();
                    if ($tokenFromHeaderResponse->IsFail())
                        return $tokenFromHeaderResponse;

                    if ($tokenFromHeaderResponse->payload->privilegeLevel < 2)
                        return NewAuthResponse(403, "Not the correct privilege level", $tokenFromHeaderResponse->payload);

                    return $this->userController->ReadUser($tokenFromHeaderResponse->payload->username)->WithToken($tokenFromHeaderResponse->payload);
                break;
                case "POST":
                    $usernameResponse = NewJsonParamRequest("username");
                    if ($usernameResponse->IsFail())
                        return $usernameResponse;

                    $passwordResponse = NewJsonParamRequest("password");
                    if ($passwordResponse->IsFail())
                        return $passwordResponse;

                    $readUserResponse = $this->userController->ReadUser($usernameResponse->payload);
                    if ($readUserResponse->IsFail())
                        return $readUserResponse;
                    
                    if ($readUserResponse->payload->password != $passwordResponse->payload)
                        return NewResponse(401, "Credentials incorrect");   

                    return $this->tokenHandler->CreateNewTokenFromUser($readUserResponse->payload);
                break;
                case "DELETE":
                    $tokenFromHeaderResponse = $this->tokenHandler->GetTokenFromHeader();
                    if ($tokenFromHeaderResponse->IsFail())
                        return $tokenFromHeaderResponse;
                    
                    if ($tokenFromHeaderResponse->payload->privilegeLevel < 2)
                        return NewAuthResponse(403, "Not the correct privilege level", $tokenFromHeaderResponse->payload);
        
                    return $this->userController->DeleteUser($tokenFromHeaderResponse->payload->username)->WithToken($tokenFromHeaderResponse->payload);
                break;
                default:
                    return NewResponse(400, "Invalid request method");
            }
        }
    }
?>