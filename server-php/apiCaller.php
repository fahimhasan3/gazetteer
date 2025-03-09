<?php

ini_set('memory_limit', '528M');

// $_POST['q'] = '52.516565, -1.887031';
// $_POST['q'] = 'United Kingdom';
// $_POST['lang'] = 'EN';

if (!isset($_POST['q'])) {
	$_POST['q'] = 'United Kingdom';
}
if (!isset($_POST['lang'])) {
	$_POST['lang'] = 'EN';
}

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include(__DIR__  . '/GeocodeClient.php');
include(__DIR__  . '/GeonamesClient.php');
include(__DIR__  . '/OpenWeatherClient.php');
include(__DIR__  . '/OpenExchangeRatesClient.php');
include(__DIR__  . '/WikipediaClient.php');
include(__DIR__  . '/RestCountriesClient.php');


include(__DIR__  . '/database/Database.php');
include(__DIR__  . '/database/WeatherService.php');
include(__DIR__  . '/database/CountryService.php');
include(__DIR__  . '/database/CityService.php');

$responseJson = [];

//Geocode api call
$geocodeClient = new GeocodeClient;
$q = $_POST['q'];
$geocodeResult;
if (preg_match('~[0-9]~', $q) == 1) {
	$geocodeResult = $geocodeClient->callGeocodeForCity($_POST['q'], $_POST['lang']);
} else {
	$geocodeResult = $geocodeClient->callGeocodeForCountry($_POST['q'], $_POST['lang']);
}

$responseJson['geocode'] = $geocodeResult['results'];


//Geonames api call
if (isset($geocodeResult['results']['countryCode'])) {
	$responseJson['countryNotFound'] = false;

	$countryCode = $geocodeResult['results']['countryCode'];

	$geonamesClient = new GeonamesClient;
	$geonamesResult = $geonamesClient->getCountryInfo($countryCode, $_POST['lang']);
	$responseJson['geonames'] = $geonamesResult;


	//by default load country capital
	$capital = $responseJson['geonames']['geonames'][0]['capital'];
	$geocodeResult = $geocodeClient->callGeocodeForCity($capital, $_POST['lang']);
	$responseJson['geocode'] = $geocodeResult['results'];


	//Country borders
	$strJsonFileContents = file_get_contents("countries.geojson");
	$countriesGeojson = json_decode($strJsonFileContents, true);

	foreach($countriesGeojson['features'] as $features) {
		if($responseJson['geonames']['geonames'][0]['isoAlpha3'] == $features['properties']['ISO_A3']) {
			$responseJson['borderGeometry'] = $features['geometry'];
			break;
		}
	}

	//Country stats api call
	$restCountriesClient = new RestCountriesClient;
	$countryStatsResult = $restCountriesClient->getCountryStatsByCode($countryCode);
	$responseJson['countryStats'] = $countryStatsResult;

	//Wikipedia api call
	$wikipediaClient = new WikipediaClient;
	$wikiResult = $wikipediaClient->extractIntroHtml($responseJson['geonames']['geonames'][0]['countryName']);
	$responseJson['wikiIntro'] = $wikiResult;

	//Weather api call
	$openWeatherClient = new OpenWeatherClient;
	$weatherResult = $openWeatherClient->getCurrentWeatherByCity($capital, $countryCode);
	$weatherError = false;
	if ($weatherResult['cod'] == '404') {
		$weatherError = true;
	}

	$responseJson['weatherError'] = $weatherError;
	$responseJson['weather'] = $weatherResult;

	//Currency api call
	$exchangeRatesClient = new OpenExchangeRatesClient;
	$responseJson['exchangeRates'] = $exchangeRatesClient->getLatest();
	$responseJson['currencies'] = $exchangeRatesClient->getCurrencies();


	//Saving to Database
	$config = require __DIR__ . '/database/config.php';
	$db = new Database($config);
	//Country
	$countryService = new CountryService($db);
	$result = $countryService->getByName($responseJson['geonames']['geonames'][0]['countryName']);
	$countryId;
	if (!$result) {
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
	$district = isset($responseJson['components']['state_district']) ? $responseJson['geocode']['state_district'] : "";
	$state = isset($responseJson['components']['state']) ? $responseJson['geocode']['state'] : "";
	$cityService = new CityService($db);
	$result = $cityService->getByNameAndCountry($capital, $countryId);
	$cityId;
	if (!$result) {
		$cityId = $cityService->insertRow($capital, $countryId, $district, $state);
	} else {
		$cityId = $result['id'];
	}


	//Weather
	if (!$weatherError) {
		$date = date('Y-m-d');
		$weatherService = new WeatherService($db);
		$result = $weatherService->getWeatherByCityAndDate($cityId, $date);
		if (!$result) {
			$weather = $responseJson['weather']['weather'][0]['main'];
			$tempMax = $responseJson['weather']['main']['temp_max'];
			$tempMin = $responseJson['weather']['main']['temp_min'];
			$description = $responseJson['weather']['weather'][0]['description'];
			$weatherService->insertRow($cityId, $weather, $tempMax, $tempMin, $date, $description);
		}

		//Last 7 days weather
		$responseJson['weatherHistory'] = $weatherService->getLast6WeatherByCity($cityId);
	}
} else {
	$responseJson['countryNotFound'] = true;
}


header('Content-Type: application/json; charset=UTF-8');
echo json_encode($responseJson, JSON_UNESCAPED_UNICODE);
