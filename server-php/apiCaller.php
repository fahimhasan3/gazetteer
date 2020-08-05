<?php

if (!isset($_POST['q'])) {
	$_POST['q'] = 'Birmingham, England';
}
if (!isset($_POST['lang'])) {
	$_POST['lang'] = 'EN';
}

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include($_SERVER['DOCUMENT_ROOT'] . '/server-php/GeocodeClient.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server-php/GeonamesClient.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server-php/OpenWeatherClient.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server-php/OpenExchangeRatesClient.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server-php/WikipediaClient.php');


include($_SERVER['DOCUMENT_ROOT'] . '/server-php/database/Database.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server-php/database/WeatherService.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server-php/database/CountryService.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server-php/database/CityService.php');

$responseJson = [];

//Geocode api call
$geocodeClient = new GeocodeClient;
$geocodeResult = $geocodeClient->callGeocode($_POST['q'], $_POST['lang']);
$responseJson['geocode'] = $geocodeResult['results'];
$cityName = $responseJson['geocode']['city'];

//Wikipedia api call
$wikipediaClient = new WikipediaClient;
$wikiResult = $wikipediaClient->extractIntroHtml($cityName);
$responseJson['wikiIntro'] = $wikiResult;

//Geonames api call
$countryCode = $geocodeResult['results']['countryCode'];
$geonamesClient = new GeonamesClient;
$geonamesResult = $geonamesClient->getCountryInfo($countryCode, $_POST['lang']);
$responseJson['geonames'] = $geonamesResult;


//Weather api call
$openWeatherClient = new OpenWeatherClient;
$responseJson['weather'] = $openWeatherClient->getCurrentWeatherByCity($cityName, $countryCode);


//Currency api call
$exchangeRatesClient = new OpenExchangeRatesClient;
$responseJson['exchangeRates'] = $exchangeRatesClient->getLatest();
$responseJson['currencies'] = $exchangeRatesClient->getCurrencies();


//Saving to Database
$db = new Database;
//Country
$countryService = new CountryService($db);
$result = $countryService->getByName($responseJson['geonames']['geonames'][0]['countryName']);
$countryId;
if(!$result) {
	$countryName = $responseJson['geonames']['geonames'][0]['countryName'];
	$continent = $responseJson['geonames']['geonames'][0]['continentName'];
	$population = $responseJson['geonames']['geonames'][0]['population'];
	$capital = $responseJson['geonames']['geonames'][0]['capital'];
	$currency = $responseJson['geonames']['geonames'][0]['currencyCode'];
	$countryId = $countryService->insertRow($countryName, $continent, $population, $capital, $currency);
} else {
	$countryId = $result['id'];
}

//City
$cityName = $responseJson['geocode']['city'];
$district = isset($responseJson['components']['state_district']) ? $responseJson['geocode']['state_district'] : "";
$state = isset($responseJson['components']['state']) ? $responseJson['geocode']['state'] : "";
$cityService = new CityService($db);
$result = $cityService->getByNameAndCountry($cityName, $countryId);
$cityId;
if(!$result) {
	$cityId = $cityService->insertRow($cityName, $countryId, $district, $state);
} else {
	$cityId = $result['id'];
}


//Weather
$date = date('Y-m-d');
$weatherService = new WeatherService($db);
$result = $weatherService->getWeatherByCityAndDate($cityId, $date);
if(!$result) {
	$weather = $responseJson['weather']['weather'][0]['main'];
	$temperature = $responseJson['weather']['main']['temp'];
	
	$weatherService->insertRow($cityId, $weather, $temperature, $date);
}

//Last 7 days weather
$responseJson['weatherHistory'] = $weatherService->getLast6WeatherByCity($cityId);

header('Content-Type: application/json; charset=UTF-8');
echo json_encode($responseJson, JSON_UNESCAPED_UNICODE);

?>