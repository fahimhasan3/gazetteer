<?php

    class OpenWeatherClient{
        public function getCurrentWeatherByCity($cityName, $countryCode) {
            $apiKey = '0e4cc63ed1d4456810f6bfef81418c24';
            $url = 'api.openweathermap.org/data/2.5/weather?q='. urlencode($cityName) . ','. urlencode($countryCode) . '&appid=' . urlencode($apiKey);
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