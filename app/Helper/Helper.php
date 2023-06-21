<?php
if (!function_exists('replacePlaceholders')) {
    function replacePlaceholders($text, $data)
    {
        foreach ($data as $key => $value) {
            $placeholder = '{' . $key . '}';
            $text = str_replace($placeholder, $value, $text);
        }
        return $text;
    }
}

if (!function_exists('generateRandomPassword')) {
    function generateRandomPassword($length = 10) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = mt_rand(0, strlen($characters) - 1);
            $password .= $characters[$randomIndex];
        }

        return $password;
    }

}
