<?php
class OpenWeatherClient
{
    private $apiKey;

    public function __construct()
    {
        $config = require __DIR__ . '/api-config.php';
        $this->apiKey = $config['open_weather_api_key'];
    }

    public function getCurrentWeatherByCity($cityName, $countryCode) {
        $url = 'api.openweathermap.org/data/2.5/weather?q='. urlencode($cityName) . ','. urlencode($countryCode) 
            . '&appid=' . urlencode($this->apiKey);
        $handle = curl_init($url);
    
        curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: text/plain; charset=UTF-8'));
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $json_result = curl_exec($handle);
        $jsonObj = json_decode($json_result, true);
        return $jsonObj;}
}
?>