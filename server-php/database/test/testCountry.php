<?php
    include('../CountryService.php');
    include('../DatabaseConnection.php');

    $config = require __DIR__ . '/../config.php';
    $db = DatabaseConnection::create($config);
    $countryService = new CountryService($db);

    $name = 'Italy';
    $continent = 'Europe';
    $population = 66000000;
    $capital = 'Rome';
    $currency = 'EUR';

    $result = $countryService->getByName($name);
    echo 'result ' . print_r($result);
    if(!$result) {
        $newId = $countryService->insertRow($name, $continent, $population, $capital, $currency);
        echo 'new country ' . $newId . ' ';
    } else {
        echo 'db country id = ' . $result['id'];
    }
    



    $result = $countryService->getByName($name);
    echo $result['name'] . ' ' . $result['continent'];
    

?>