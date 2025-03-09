<?php
    include(__DIR__ . '/../RestCountriesClient.php');

    $client = new RestCountriesClient;
    $countryCode = 'GB';
    $result = $client->getCountryStatsByCode($countryCode);

    echo 'result ' . print_r($result);
?>