<?php
    include(__DIR__ . '/../GeonamesClient.php');

    $client = new GeonamesClient;
    $countryCode = 'GB';
    $lang = 'EN';
    $info = $client->getCountryInfo($countryCode, $lang);

    echo 'info ' . print_r($info); 
?>