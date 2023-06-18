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
