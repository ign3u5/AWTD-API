<?php
class JsonParser
{
    public function encode($data)
    {
        $json = json_encode($data);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Json encoding error");
        }

        return $json;
    }
    public function decode($json)
    {
        $result = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Json encoding error");
        }

        // It must be a standard JSON
        if (is_array($result) == false) {
            throw new Exception("Json encoding result is not an array");
        }

        return $result;
    }
}
?>