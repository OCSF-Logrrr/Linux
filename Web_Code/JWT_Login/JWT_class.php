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
        return true;
    }
}
