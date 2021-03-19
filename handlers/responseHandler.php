<?php
require_once __DIR__."/../models/response.php";

function NewResponse($statusCode, $message)
{
    if ($statusCode > 399)
        return new FailResponse($statusCode, $message);
    return new SuccessResponse($statusCode, $message);
}
function NewResponseWithPayload($statusCode, $message, $payload)
{
    if ($statusCode > 399)
        return new FailResponseWithPayload($statusCode, $message, $payload);
    return new SuccessResponseWithPayload($statusCode, $message, $payload);
}
function NewAuthResponse($statusCode, $message, $token)
{
    if ($statusCode > 399)
        return new AuthFailResponse($statusCode, $message, $token);
    return new AuthSuccessResponse($statusCode, $message, $token);
}
function NewAuthResponseWithPayload($statusCode, $message, $payload, $token)
{
    if ($statusCode > 399)
        return new AuthFailResponseWithPayload($statusCode, $message, $payload, $token);
    return new AuthSuccessResponseWithPayload($statusCode, $message, $payload, $token);
}
function HanldeOptionsRequest()
{
    if ($_SERVER['REQUEST_METHOD'] == "OPTIONS")
        return NewResponse(200, "Options request response"); 
}
?>