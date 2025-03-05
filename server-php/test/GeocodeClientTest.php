<?php
    include(__DIR__ . '/../GeocodeClient.php');

    $client = new GeocodeClient;
    $country = 'United Kingdom';
    $lang = 'EN';
    $city = 'London';
    $countryGeocode = $client->callGeocodeForCountry($country, $lang);
    $cityGeocode = $client->callGeocodeForCity($city, $lang);

    echo 'countryGeocode ' . print_r($countryGeocode); 
    echo 'cityGeocode ' . print_r($cityGeocode); 
?>