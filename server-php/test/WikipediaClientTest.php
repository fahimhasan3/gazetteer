<?php
    include(__DIR__ . '/../WikipediaClient.php');

    $client = new WikipediaClient;
    $cityName = 'London';
    $result = $client->extractIntroHtml($cityName);

    echo 'result ' . print_r($result);
?>