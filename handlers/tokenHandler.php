<?php
require_once __DIR__."/../models/token.php";
require_once __DIR__."/../handlers/responseHandler.php";
require_once __DIR__."/../controllers/usersController.php";

class TokenHandler
{
    private $jwtHandler;
    private $userController;
    public function __construct()
    {
        $database = new Database();
        $connection = $database->getConnection();
        $this->userController = new UsersController($connection);
        $this->jwtHandler = new JWTHandler();
    }
    public function GetTokenFromHeader()
    {
        if (!isset(getallheaders()["Token"]))
            return NewResponse(401, "No valid authorisation");

        $requestToken = getallheaders()["Token"];

        $decodeResponse = $this->jwtHandler->Decode($requestToken);
        if ($decodeResponse->IsFail())
            return $decodeResponse;
        
        $newTokenResponse = Token::Create($decodeResponse->payload);
        if ($newTokenResponse->IsFail())
            return $newTokenResponse;
        
        $newToken = $newTokenResponse->payload;
        if ($newToken->exp < time())
            return NewResponse(403, "Authentication has expired");

        if ($this->userController->ReadUser($newToken->username)->IsFail())
            return NewResponse(401, "User no longer exists");
        
        return NewResponseWithPayload(200, "Token is valid and has not expired", $newToken);
    }

    public function CreateJWTToken($token)
    {
        return $this->jwtHandler->Encode($token);
    }

    public function CreateNewTokenFromUser($user)
    {
        $token = new Token();
        $token->username = $user->username;
        $token->privilegeLevel = $user->privilegeLevel;
        $token->firstName = $user->firstName;
        $token->lastName = $user->lastName;
        return $token;
    }
}

?>