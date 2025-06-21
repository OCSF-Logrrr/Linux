<?php
class JWT {
    private $secretKey;

    public function __construct() {
        $this->secretKey = "WWWWHHHHSSSSOOOOOCCCCCSSSSSFFFFF";
    }

    private function base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private function base64UrlDecode($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public function hashing($payload) {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, $this->secretKey, true);
        $signatureEncoded = $this->base64UrlEncode($signature);

        return $headerEncoded . "." . $payloadEncoded . "." . $signatureEncoded;
    }

    public function dehashing($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        list($header, $payload, $signature) = $parts;

        $expectedSig = hash_hmac('sha256', $header . "." . $payload, $this->secretKey, true);
        $expectedSig = $this->base64UrlEncode($expectedSig);

        if (!hash_equals($expectedSig, $signature)) {
            $log = "[" . date("Y-m-d H:i:s") . "] JWT 변조 탐지됨 - IP: " . $_SERVER['REMOTE_ADDR'] . " - 토큰: " . $token . "\n";
            error_log($log, 3, __DIR__ . "/jwt_tamper.log");
            return false;
        }

        return json_decode($this->base64UrlDecode($payload), true);
    }
}
?>
