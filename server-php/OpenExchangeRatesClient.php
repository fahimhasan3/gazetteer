<?php
class OpenExchangeRatesClient
{
    private $apiKey;

    public function __construct()
    {
        $config = require __DIR__ . '/api-config.php';
        $this->apiKey = $config['open_exchange_rates_api_key'];
    }

    public function getLatest()
    {
        echo 'api key ' . $this->apiKey . "\n";
        $url = 'https://openexchangerates.org/api/latest.json?app_id=' . urlencode($this->apiKey);
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
        $filePath = __DIR__ . "/currencies.json";
        $strJsonFileContents = file_get_contents($filePath);
        // Convert to array 
        $jsonObj = json_decode($strJsonFileContents, true);
        return $jsonObj;
        
    }

}