<?php
require_once __DIR__."/../handlers/tokenHandler.php";

abstract class Response
{
    protected $statusCode;
    protected $message;
    protected $responseContent;
    public function __construct($statusCode, $message)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->responseContent = array();
    }
    abstract public function IsFail();
    protected function CreateResponseContent()
    {
        $this->responseContent["message"] = $this->message;
    }
    abstract public function WithToken($token);
    public function SendResponse()
    {
        $this->CreateResponseContent();
        http_response_code($this->statusCode);
        echo json_encode($this->responseContent);
    }
}
abstract class AuthResponse extends Response
{
    private $token;
    public function __construct($statusCode, $message, $token)
    {
        parent::__construct($statusCode, $message);
        $this->SetToken($token);
    }
    private function SetToken($token)
    {
        $tokenHandler = new TokenHandler();
        $tokenHandlerResponse = $tokenHandler->CreateJWTToken($token);
        if ($tokenHandlerResponse->IsFail())
            return $tokenHandlerResponse;
        $this->token = $tokenHandlerResponse->payload;
    }
    public function SendResponse()
    {
        $this->CreateResponseContent();
        http_response_code($this->statusCode);
        header("Token: ".$this->token);
        echo json_encode($this->responseContent);
    }
}

abstract class ResponseWithPayload extends Response
{
    public $payload;
    public function __construct($statusCode, $message, $payload)
    {
        parent::__construct($statusCode, $message);
        $this->payload = $payload;
    }
    protected function CreateResponseContent()
    {
        $this->responseContent["message"] = $this->message;
        $this->responseContent["data"] = $this->payload;
    }
}
abstract class AuthResponseWithPayload extends AuthResponse
{
    public $payload;
    public function __construct($statusCode, $message, $payload, $token)
    {
        parent::__construct($statusCode, $message, $token);
        $this->payload = $payload;
    }
    protected function CreateResponseContent()
    {
        $this->responseContent["message"] = $this->message;
        $this->responseContent["data"] = $this->payload;
    }
}

class SuccessResponse extends Response
{
    public function IsFail()
    {
        return false;
    }
    public function WithToken($token)
    {
        return new AuthSuccessResponse($this->statusCode, $this->message, $token);
    }
}
class SuccessResponseWithPayload extends ResponseWithPayload
{
    public function IsFail()
    {
        return false;
    }
    public function WithToken($token)
    {
        return new AuthSuccessResponseWithPayload($this->statusCode, $this->message, $this->payload, $token);
    }
}
class FailResponse extends Response
{
    public function IsFail()
    {
        return true;
    }
    public function WithToken($token)
    {
        return new AuthFailResponse($this->statusCode, $this->message, $token);
    }
}
class FailResponseWithPayload extends ResponseWithPayload
{
    public function IsFail()
    {
        return true;
    }
    public function WithToken($token)
    {
        return new AuthFailResponseWithPayload($this->statusCode, $this->message, $this->payload, $token);
    }
}
class AuthSuccessResponse extends AuthResponse
{
    public function IsFail()
    {
        return false;
    }
    public function WithToken($token)
    {
        $this->token = $token;
    }
}
class AuthSuccessResponseWithPayload extends AuthResponseWithPayload
{
    public function IsFail()
    {
        return false;
    }
    public function WithToken($token)
    {
        $this->token = $token;
    }
}
class AuthFailResponse extends AuthResponse
{
    public function IsFail()
    {
        return true;
    }
    public function WithToken($token)
    {
        $this->token = $token;
    }
}
class AuthFailResponseWithPayload extends AuthResponseWithPayload
{
    public function IsFail()
    {
        return true;
    }
    public function WithToken($token)
    {
        $this->token = $token;
    }
}
?>