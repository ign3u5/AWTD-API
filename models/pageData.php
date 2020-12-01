<?php
require_once __DIR__."/../handlers/responseHandler.php";
require_once "pageContent.php";
class PageData{
    public $pageName;
    public $pageContents = array();

    public function Map($potentialPage)
    {
        $this->pageName = $potentialPage["pageName"];
        foreach ($potentialPage["pageContents"] as $pageContentRequestData)
        {
            $pageContentResponse = PageContent::Create($pageContentRequestData);
            if ($pageContentResponse->IsFail())
                return $pageContentResponse;
            array_push($this->pageContents, $pageContentResponse->payload);
        }
        return NewResponseWithPayload(200, "Page data mapped", $this);
    }

    public static function Create($requestContent)
    {
        if (isset($requestContent["pageName"]) &&
        isset($requestContent["pageContents"]))
        {
            $pageData = new PageData();
            return $pageData->Map($requestContent);
        }
        return NewResponse(400, "Invalid request content");
    }
}
?>