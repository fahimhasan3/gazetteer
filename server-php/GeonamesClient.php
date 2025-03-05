<?php

class GeonamesClient
{
    private $username;

    public function __construct()
    {
        $config = require __DIR__ . '/api-config.php';
        $this->username = $config['geonames_username'];
    }

    public function getCountryInfo($countryCode, $lang)
    {
        $handle = curl_init('http://api.geonames.org/countryInfoJSON?formatted=true&lang=' . urlencode($lang) 
            . '&country=' . urlencode($countryCode) . '&username=' . urlencode($this->username) . '&style=full');

        curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: text/plain; charset=UTF-8'));
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $json_result = curl_exec($handle);
        $jsonObj = json_decode($json_result, true);
        return $jsonObj;
    }
}

?>