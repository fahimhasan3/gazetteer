<?php
    include(__DIR__ . '/../OpenWeatherClient.php');

    //Currency api call
    $client = new OpenWeatherClient;
    $cityName = 'Birmingham';
    $countryCode = 'GB';
    $result = $client->getCurrentWeatherByCity($cityName, $countryCode);

    echo 'result ' . print_r($result);
?>