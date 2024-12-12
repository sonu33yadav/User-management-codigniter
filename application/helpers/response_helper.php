<?php
if (!function_exists('response_format')) {
    function response_format($status, $message, $data = [])
    {
        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ];
    }
}