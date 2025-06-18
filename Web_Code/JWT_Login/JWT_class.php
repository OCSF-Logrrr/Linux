<?php
class JWT {
    private $secretKey;

    public function __construct() {
        $this->secretKey = "WWWWHHHHSSSSOOOOCCCCSSSSFFFLLLLOOOOGGGGRRRRRRRRRRRRRR";
    }

    private function base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    public function hashing($payload) {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $header = $this->base64UrlEncode(json_encode($header));
        $payload = $this->base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', $header . "." . $payload, $this->secretKey, true);
        $signature = $this->base64UrlEncode($signature);

        return $header . "." . $payload . "." . $signature;
    }

    public function dehashing($token) {
        list($header, $payload, $signature) = explode('.', $token);
        $new_signature = hash_hmac('sha256', "$header.$payload", $this->secretKey, true);
        $new_signature = $this->base64UrlEncode($new_signature);
        if ($signature===$new_signature){
            return true;
        }
        else{
            echo "<script>alert('서명 검증 실패');</script>";
            return false;
        }
    }
}
