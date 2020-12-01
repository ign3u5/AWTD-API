<?php
require_once __DIR__."/../models/token.php";
require_once __DIR__."/../handlers/responseHandler.php";

class TokenHandler
{
    private $jwtHandler;
    public function __construct()
    {
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
        return $this->CreateJWTToken($token);
    }
}

?>