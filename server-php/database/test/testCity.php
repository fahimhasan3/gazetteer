<?php
    include('../CityService.php');
    include('../DatabaseConnection.php');

    $config = require __DIR__ . '/../config.php';
    $db = DatabaseConnection::create($config);
    $cityService = new CityService($db);

    $name = 'Leeds';
    $countryId = 1;
    $district = 'West Yorkshire';
    $state = 'England';

    $newId = $cityService->insertRow($name, $countryId, $district, $state);
    echo $newId . ' ';



    $result = $cityService->getByNameAndCountry($name, $countryId);
    echo $result['name'];
    

?>