<?php
    class WikipediaClient {
        public function extractIntroHtml($cityName) {
            $url = 'https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro&explaintext&redirects=1&titles=' . urlencode($cityName);
            $handle = curl_init($url);
        
            curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: text/plain; charset=UTF-8'));
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $json_result = curl_exec($handle);
            $jsonObj = json_decode($json_result, true);
            $extract = current($jsonObj['query']['pages'])['extract'];
            return $extract;
        }
    }
?>