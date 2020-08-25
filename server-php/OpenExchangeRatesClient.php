<?php
class OpenExchangeRatesClient
{
    public function getLatest()
    {
        $apiKey = '9a183a2a937b469cb0705621afa39598';
        $url = 'https://openexchangerates.org/api/latest.json?app_id=' . urlencode($apiKey);
        $handle = curl_init($url);

        curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: text/plain; charset=UTF-8'));
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $json_result = curl_exec($handle);
        $jsonObj = json_decode($json_result, true);
        return $jsonObj;
    }

    public function getCurrencies(){
        $strJsonFileContents = file_get_contents("currencies.json");
        // Convert to array 
        $jsonObj = json_decode($strJsonFileContents, true);
        return $jsonObj;
        
    }

}
