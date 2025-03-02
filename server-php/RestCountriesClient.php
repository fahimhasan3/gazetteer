<?php

    class RestCountriesClient {
        public function getCountryStatsByCode($countryCode) {
            $url = 'https://restcountries.com/v3.1/alpha?codes=' . urlencode($countryCode);
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