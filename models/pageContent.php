<?php
require_once __DIR__."/../handlers/responseHandler.php";

class PageContent{
    public $contentId;
    public $content;
    
    public function __construct($pageContentRequestData)
    {
        $this->contentId = $pageContentRequestData["contentId"];
        $this->content = $pageContentRequestData["content"];
    }

    public static function Create($requestContent){
        if (isset($requestContent["contentId"]) &&
        isset($requestContent["content"]))
        {
            return NewResponseWithPayload(200, "Page content is valid", new PageContent($requestContent));
        }
        return NewResponse(400, "Invalid request content");
    }
}
?>