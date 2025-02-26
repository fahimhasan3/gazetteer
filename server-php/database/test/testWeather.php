<?php
    include('../WeatherService.php');
    include('../Database.php');

    $config = require __DIR__ . '/../config.php';
    $db = new Database($config);
    $weatherService = new WeatherService($db);

    $city_id = 1;
    $weather = 'Clear';
    $tempMax = 25;
    $tempMin = 15;
    $date = date('Y-m-d');
    $description = 'test description';

    $result = $weatherService->getWeatherByCityAndDate($city_id, $date);
    if(!$result) {
        $newId = $weatherService->insertRow($city_id, $weather, $tempMax, $tempMin, $date, $description);
        echo 'new weather id = ' . $newId . " \n";
    } else {
        echo 'existing record id = ' . $result['id'] . "\n";
    }

    $results = $weatherService->getLast6WeatherByCity($city_id);
    foreach($results as $item) {
        echo 'temp_max ' . $item['temp_max'];
    }

?>