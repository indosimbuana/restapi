<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Token
{
    function Cek()
    {
        $CI = &get_instance();

        $token = $CI->input->get_request_header('Token', TRUE);
        if ($token == NULL) {
            $data['status'] = false;
            $data['code'] = 401;
            $data['message'] = "Unauthorized - Null Token";
            return $data;
        } else {
            try {
                $key = "rahasia";

                $decoded = JWT::decode($token, $key, array('HS256'));

                $currentTimestamp = time();
                $userLastActivity = $decoded;
                $timeLapse = (($currentTimestamp - $userLastActivity) / 60);

                if ($timeLapse >= 5) {
                    $data['status'] = false;
                    $data['code'] = 401;
                    $data['message'] = "Unauthorized - Token Expired";
                    return $data;
                } else {
                    $data['status'] = true;
                    $data['code'] = 200;
                    $data['message'] = "OK";
                    return $data;
                }
            } catch (\Firebase\JWT\SignatureInvalidException | UnexpectedValueException $e) {
                $data['status'] = false;
                $data['code'] = 401;
                $data['message'] = "Unauthorized - Wrong Token";
                return $data;
            }
        }
    }
}
