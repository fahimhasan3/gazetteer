<?php

class GeocodeClient
{
	public function callGeocode($q, $lang)
	{
		$jsonObj = $this->makeCurlRequest($q, $lang);
		$searchResult = $this->findCityFromJson($jsonObj);
		if (!$searchResult['results'] && preg_match('~[0-9]~', $q) == 1) {
			$cityName = $jsonObj['results'][0]['components']['city'];
			$countryName = $jsonObj['results'][0]['components']['country'];
			$q = $cityName . ', ' . $countryName;
			$jsonObj = $this->makeCurlRequest($q, $lang);
			$searchResult = $this->findCityFromJson($jsonObj);
		} 
		return $searchResult;
	}

	private function makeCurlRequest($q, $lang)
	{
		$geocodeApiKey = "0d7291d488994a31bd2bbc2605770c6b";
		$url = 'https://api.opencagedata.com/geocode/v1/json?q=' . urlencode($q) . '&key=' . urlencode($geocodeApiKey) . '&language=' . urlencode($lang) . '&pretty=1';

		$handle = curl_init($url);

		curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: text/plain; charset=UTF-8'));
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($handle);
		$jsonObj = json_decode($result, true);

		return $jsonObj;
	}

	private function findCityFromJson($jsonObj)
	{
		$searchResult = [];
		$searchResult['results'] = [];
		foreach ($jsonObj['results'] as $entry) {
			if ($entry['components']['_category'] == 'place' && $entry['components']['_type'] == 'city') {
				$searchResult['results'] = $this->createResponseArray($entry);
				break;
			}
		}
		return $searchResult;
	}

	

	private function createResponseArray($entry)
	{
		$temp = [];
		$temp['source'] = 'opencage';
		$temp['formatted'] = $entry['formatted'];
		$temp['geometry']['lat'] = $entry['geometry']['lat'];
		$temp['geometry']['lng'] = $entry['geometry']['lng'];
		$temp['countryCode'] = strtoupper($entry['components']['country_code']);
		$temp['city'] = $entry['components']['city'];
		if (isset($entry['components']['state_district'])) {
			$temp['state_district'] = $entry['components']['state_district'];
		} 
		 if (isset($entry['components']['state'])) {
			$temp['state'] = $entry['components']['state'];
		}
		return $temp;
	}
}
