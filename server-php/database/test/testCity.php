<?php
    include('../CityService.php');
    include('../Database.php');

    $db = new Database;
    $cityService = new CityService($db);

    $name = 'Leeds';
    $countryId = 1;
    $district = 'example';

    $newId = $cityService->insertRow($name, $countryId, $district);
    echo $newId . ' ';



    $result = $cityService->getByNameAndCountry($name, $countryId);
    echo $result['name'];
    

?>