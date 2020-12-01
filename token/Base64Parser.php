<?php
class Base64Parser
{
    public function encode($data)
    {
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }

    public function decode($data)
    {
        if ($remainder = strlen($data) % 4) {
            $paddingLength = 4 - $remainder;
            $data .= str_repeat('=', $paddingLength);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }
}
?>