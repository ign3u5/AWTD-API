<?php
class ContactFormData{
    public $name;
    public $company;
    public $email;
    public $message;
    public function __construct($contactFormRequestData)
    {
        $this->name = $contactFormRequestData["name"];
        $this->email = $contactFormRequestData["email"];
        $this->message = $contactFormRequestData["message"];
        if (isset($contactFormRequestData["company"]))
            $this->company = $contactFormRequestData["company"];
    }
    public static function Create($requestContent)
    {
        if (isset($requestContent["name"]) &&
        isset($requestContent["email"]) &&
        isset($requestContent["message"]))
        {
            return NewResponseWithPayload(200, "Successfully received valid contact form data", new ContactFormData($requestContent));
        }
        return NewResponse(400, "Invalid contact form data");
    }
}

?>