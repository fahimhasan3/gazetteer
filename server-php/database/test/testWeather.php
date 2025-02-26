<?php
    include('../WeatherService.php');
    include('../Database.php');

    $config = require __DIR__ . '/../config.php';
    $db = new Database($config);
    $weatherService = new WeatherService($db);

    $city_id = 1;
    $weather = 'Clear';
    $temperature = 270.50;
    $date = date('Y-m-d');

    $result = $weatherService->getWeatherByCityAndDate($city_id, $date);
    if(!$result) {
        $newId = $weatherService->insertRow($city_id, $weather, $temperature, $date);
        echo 'new weather id = ' . $newId . ' ';
    } else {
        echo 'existing record id = ' . $result['id'];
    }

    



    $results = $weatherService->getLast7WeatherByCity($city_id);
    foreach($results as $item) {
        echo $item['temperature'] . ' ';
    }

?>