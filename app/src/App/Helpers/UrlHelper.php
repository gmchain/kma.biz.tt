<?php

namespace App\Helpers;

class UrlHelper {

    public static function encode(string $url) : string
    {
        $parts = parse_url($url);
    
        $out = '';

        if (!empty($parts['scheme']))   $out .= $parts['scheme'] . ':';
        if (!empty($parts['host']))     $out .= '//';
        if (!empty($parts['user']))     $out .= $parts['user'];
        if (!empty($parts['pass']))     $out .= ':' . $parts['pass'];
        if (!empty($parts['user']))     $out .= '@';
        if (!empty($parts['host']))     $out .= idn_to_ascii($parts['host']);
        if (!empty($parts['port']))     $out .= ':' . $parts['port'];
        if (!empty($parts['path']))     $out .= $parts['path'];
        if (!empty($parts['query']))    $out .= '?' . $parts['query'];
        if (!empty($parts['fragment'])) $out .= '#' . $parts['fragment'];
    
        return $out;
    }

    public static function getUrlLength($url) : int
    {

        $curlHandler = curl_init(self::encode($url));

        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curlHandler, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curlHandler, CURLOPT_MAXREDIRS, 5);
        curl_setopt($curlHandler, CURLOPT_HEADER, TRUE);

        $data = curl_exec($curlHandler);

        curl_close($curlHandler);

        return strlen($data);
    }

    public static function validateUrl(string $url) : bool
    {
        $url = self::encode($url);
        
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}