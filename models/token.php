<?php
require_once __DIR__."/../handlers/jwtHandler.php";
require_once __DIR__."/../handlers/responseHandler.php";

class Token
{
    public $username;
    public $privilegeLevel;
    public $firstName;
    public $lastName;
    public $exp;

    private function Map($requestToken)
    {
        $this->username = $requestToken["username"];
        $this->privilegeLevel = $requestToken["privilegeLevel"];
        $this->firstName = $requestToken["firstName"];
        $this->lastName = $requestToken["lastName"];
        $this->exp = $requestToken["exp"];
        return $this;
    }
    public static function Create($requestToken)
    {
        if (isset($requestToken["username"]) &&
        isset($requestToken["privilegeLevel"]) &&
        isset($requestToken["firstName"]) &&
        isset($requestToken["lastName"]) &&
        isset($requestToken["exp"]))
        {
            $newToken = new Token();
            return NewResponseWithPayload(200, "Token is valid", $newToken->Map($requestToken));
        }
        return NewResponse(400, "Unrecognised token");
    }
}
?>