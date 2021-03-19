<?php
require_once __DIR__."/../handlers/responseHandler.php";

class EmailHandler
{
    private $to;
    private $subject;
    private $message;

    public function __construct($to)
    {
        $this->to = $to;
    }
    public function EmailContactFormData($contactFormData)
    {
        $this->subject = "New message from Skylab Project (ATWD)";
        $this->message = "Message from: " . $contactFormData->name . 
        ".\n Email: " . $contactFormData->email . 
        ".\n Message: " . $contactFormData->message . ".\n";
        if (isset($contactFormData->company))
            $this->message .= " Company Name: " . $contactFormData->company . ".\n";
        
        return $this->SendEmail();
    }
    private function SendEmail()
    {
        if (mail($this->to, $this->subject, $this->message))
            return NewResponse(200, "Email successfully sent");
        return NewResponse(500, "Error sending email");
    }
}
?>