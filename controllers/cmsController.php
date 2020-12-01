<?php
    require_once __DIR__."/../models/database.php";
    require_once __DIR__."/../models/pageData.php";
    require_once __DIR__."/../models/pageContent.php";
    require_once __DIR__."/../handlers/responseHandler.php";

    class CMSController{
        private $connection;
        private $db_table = "cmstbl";

        public function __construct($database){
            $this->connection = $database;
        }

        public function ReadPage($pageName) {
            $sqlQuery = "SELECT 
            contentId,
            content FROM 
            " . $this->db_table . " WHERE 
            pageName = :pageName";

            $statement = $this->connection->prepare($sqlQuery);

            Sanitise($pageName);

            $statement->bindParam(":pageName", $pageName);

            if ($statement->execute())
            {
                $pageData = new PageData();
                $pageData->pageName = $pageName;
                while ($row = $statement->fetch(PDO::FETCH_ASSOC))
                {
                    $pageContentResponse = PageContent::Create($row);
                    if ($pageContentResponse->IsFail())
                        return $pageContentResponse;
                    array_push($pageData->pageContents, $pageContentResponse->payload);
                }
                return NewResponseWithPayload(200, "Successfully collected page data", $pageData);
            }
            return RespondWithExecutionError();
        }

        public function UpdatePage($pageData) 
        {
            foreach($pageData->pageContents as $pageContent)
            {
                $sqlQuery = "UPDATE 
                " . $this->db_table . " SET 
                content = :content WHERE 
                pageName = :pageName AND contentId = :contentId";
                $statement = $this->connection->prepare($sqlQuery);

                Sanitise($pageData->pageName);
                Sanitise($pageContent->contentId);
                Sanitise($pageContent->content);

                $statement->bindParam(":pageName", $pageData->pageName);
                $statement->bindParam(":contentId", $pageContent->contentId);
                $statement->bindParam(":content", $pageContent->content);

                if(!$statement->execute())
                    return RespondWithExecutionError();
                if ($statement->rowCount() < 1)
                    return NewResponse(404, "Page data not found");
            }
            return NewResponse(201, "Successfully updated page");
        }
    }
?>