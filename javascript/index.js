let geocoderClickCounter = 0;
let jsonData = [];
let countryCurrencyCode;
let latitude = 0;
let longitude = 0;
let zoom = 6;
var mymap;
var marker;
var countryNotFound = false;

var currency_symbols = {
	'BGN': 'лв', //Bulgariaan iev
	'CHF': 'CHF', //Swiss franc
	'CZK': 'Kč', //Czech koruna	
	'DKK': 'kr', //Danish krone
	'USD': '$', // US Dollar
	'EUR': '€', // Euro
	'CRC': '₡', // Costa Rican Colón
	'GBP': '£', // British Pound Sterling
	'ILS': '₪', // Israeli New Sheqel
	'INR': '₹', // Indian Rupee
	'JPY': '¥', // Japanese Yen
	'KRW': '₩', // South Korean Won
	'NGN': '₦', // Nigerian Naira
	'PHP': '₱', // Philippine Peso
	'PLN': 'zł', // Polish Zloty
	'PYG': '₲', // Paraguayan Guarani
	'THB': '฿', // Thai Baht
	'UAH': '₴', // Ukrainian Hryvnia
	'VND': '₫', // Vietnamese Dong
};

var currentCountryLayer;

function callApi() {
	let searchValue = $('#searchBar').val();
	if (currentCountryLayer != null) {
		mymap.removeLayer(currentCountryLayer);
	}

	if (searchValue) {
		showLoader();
		disableSearchButton();
		$.post('server-php/apiCaller.php', { q: searchValue, lang: "EN" }, function (data) {
			try {
				let jsonString = JSON.stringify(data);
				jsonData = data;
				if (data.countryNotFound == false) {
					countryNotFound = false;
					updateLatitudeLongitude();
					populateGeneralInfo();
					clearweather();
					if (jsonData.weatherError == false) {
						populateWeather();
						populateWeatherHistory();
					} else {
						showWeatherWarning();
					}
					populateCurrency();
					populateCountryStats();
					displayBorders();
				} else {
					countryNotFound = true;
					showWeatherWarning();
					showIntroWarning();
					showCountryStatsWarning();
				}
	
	
				enableSearchButton();
				console.log('hiding loader...');
				hideLoader();
			} catch (error) {
				console.error('Error reading data:', error);
				return;
			}
			
		});
		geocoderClickCounter++;
		if (geocoderClickCounter >= 5) {
			disableSearchButton();
		}
	}
}

function displayBorders() {
	currentCountryLayer = L.geoJson(jsonData.borderGeometry);
	currentCountryLayer.addTo(mymap);
	mymap.fitBounds(currentCountryLayer.getBounds());
}

function disableSearchButton() {
	$("#searchButton").prop('disabled', true);
}
function enableSearchButton() {
	$("#searchButton").prop('disabled', false);
}

function showGeneralInfo() {
	$('#general-info').show();
	if (countryNotFound == false) {
		$('#cityStats').show();
	}
	$('#weather').hide();
	$('#currency').hide();
	$('#statistics').hide();

	$('#showGeneralInfo').addClass('active');
	$('#showWeather').removeClass('active');
	$('#showCurrency').removeClass('active');
	$('#showStats').removeClass('active');
}

function showWeather() {
	$('#general-info').hide();
	$('#showGeneralInfo').removeClass('active');

	$('#weather').show();
	$('#cityStats').hide();
	$('#showWeather').addClass('active');

	$('#currency').hide();
	$('#statistics').hide();
	$('#showStats').removeClass('active');
	$('#showCurrency').removeClass('active');
}

function showCurrency() {
	$('#general-info').hide();
	$('#weather').hide();
	$('#currency').show();
	$('#statistics').hide();
	$('#cityStats').hide();

	$('#showGeneralInfo').removeClass('active');
	$('#showWeather').removeClass('active');
	$('#showStats').removeClass('active');
	$('#showCurrency').addClass('active');
}

function showStats() {
	$('#general-info').hide();
	$('#weather').hide();
	$('#currency').hide();
	$('#statistics').show();
	$('#cityStats').hide();

	$('#showGeneralInfo').removeClass('active');
	$('#showWeather').removeClass('active');
	$('#showCurrency').removeClass('active');
	$('#showStats').addClass('active');
}

function populateGeneralInfo() {
	$('#stateDistrict').html("");
	$('#state').html("");

	$('#IntroWarning').hide();
	if (jsonData.wikiIntro.length < 50) {
		showIntroWarning();
	} else {
		$('#IntroWarning').hide();
		$('#wikiIntro').html(jsonData.wikiIntro);
		$('#wikiIntro').show();
		$('#cityStats').show();
		$('#cityNameSection').show();
	}

	$('#countryNameTitle').html(jsonData.geonames.geonames[0].countryName);
	$('#capitalNameFormatted').html(jsonData.geocode.formatted);

	$('#stateDistrict').html(jsonData.geocode.state_district);
	$('#stateName').html(jsonData.geocode.state);
	$('#countryName').html(jsonData.geonames.geonames[0].countryName);
	$('#continentName').html(jsonData.geonames.geonames[0].continentName);
	$('#capital').html(jsonData.geonames.geonames[0].capital)

	if (jsonData.countryStats[0].flags && jsonData.countryStats[0].flags.svg) {
		$('#countryFlag').attr("src", jsonData.countryStats[0].flags.svg);
	}

	//currency
	countryCurrencyCode = jsonData.geonames.geonames[0].currencyCode;
	for (const [key, value] of Object.entries(jsonData.currencies)) {
		if (key == countryCurrencyCode) {
			let currencySymbol = '';
			if (currency_symbols[countryCurrencyCode] !== undefined) {
				currencySymbol = currency_symbols[countryCurrencyCode];
			}
			$('#countryCurrency').html(key + ", " + value + ' ' + currencySymbol);
		}
	}
}

function populateCountryStats() {
	if (!jsonData.countryStats || !Array.isArray(jsonData.countryStats) || jsonData.countryStats.length === 0) {
        console.warn("No country stats available.");
		showCountryStatsWarning();
        return;
    }

	$('#countryStatsWarning').hide();
	$('#countryStatsTable').show();

	const country = jsonData.countryStats[0];
	$('#countryStatsName').html(country.name);
	$('#countryStatsAlpha2Code').html(country.alpha2Code);
	$('#countryStatsCapital').html(country.capital);
	$('#countryStatsAlpha3Code').html(country.alpha3Code);
	$('#countryStatsRegion').html(country.region);

	if (country.languages && typeof country.languages === "object") {
        const languageArray = Object.values(country.languages);
        if (languageArray.length > 0) {
            $('#countryStatsLanguages').html(languageArray.join(', '));
        } else {
            $('#countryStatsLanguages').html("N/A");
        }
    } else {
        $('#countryStatsLanguages').html("N/A");
    }

	$('#countryStatsSubregion').html(country.subregion);
	$('#countryStatsPopulation').html(country.population);
	$('#countryStatsDemonym').html(country.demonym);
	$('#countryStatsArea').html(country.area + ' km2');

	if (country.currencies && typeof country.currencies === "object") {
		const currencyArray = Object.values(country.currencies);
	
		if (currencyArray.length > 0) {
			$('#countryStatsCurrencies').html(currencyArray.map(currency => `${currency.name} ${currency.symbol}`).join(', '));
		} else {
			$('#countryStatsCurrencies').html("N/A");
		}
	} else {
		$('#countryStatsCurrencies').html("N/A");
	}

	if (country.idd && country.idd.root && Array.isArray(country.idd.suffixes)) {
		const root = country.idd.root;
		const suffixes = country.idd.suffixes;

		if (suffixes.length > 0) {
			$('#countryStatsCallingCodes').html(suffixes.map(suffix => root + suffix).join(', '));
		} else {
			$('#countryStatsCallingCodes').html(root);
		}
	} else {
		$('#countryStatsCallingCodes').html("N/A");
	}


	if (country.flags && country.flags.svg) {
		$('#countryStatsFlag').attr("src", country.flags.svg);
	}
}

function updateLatitudeLongitude() {
	latitude = jsonData.geocode.geometry.lat;
	longitude = jsonData.geocode.geometry.lng;
	updateMapCenter();
}

function showWeatherWarning() {
	$('#weatherWarning').show();
	$('#weatherTableContainer').hide();
}

function showIntroWarning() {
	$('#IntroWarning').show();
	$('#wikiIntro').hide();
	$('#cityStats').hide()
	$('#cityNameSection').hide();
}

function showCountryStatsWarning() {
	$('#countryStatsWarning').show();
	$('#countryStatsTable').hide();
}

function clearweather() {
	$('#temperature').html('');
	$('#weatherIcon').attr("src", '');
	$("#weatherHistory tbody").empty();
	$('#pressure').html();
	$('#humidity').html();
	$('#windSpeed').html();
}

function populateWeather() {
	$('#weatherWarning').hide();
	$('#weatherTableContainer').show();
	let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', timeZoneName: 'short' };
	let today = new Date();
	let formattedDate = today.toLocaleDateString("en-US", options);
	$('#todayDate').html(formattedDate);

	let kelvinTemp = jsonData.weather.main.temp;
	let celsiusTemp = kelvinTemp - 273.15;
	$('#temperature').html(celsiusTemp.toFixed(2) + ' C°');

	let sky = jsonData.weather.weather[0].main;
	let src = '';
	switch (sky) {
		case 'Clouds': src = 'images/cloudy.png';
			break;
		case 'Rain': src = 'images/rain.png';
			break;
		case 'Clear': src = 'images/sunny.png';
			break;
		case 'Extreme': src = 'images/storm.png';
			break;
		case 'Snow': src = 'images/snowing.png';
			break;
		case 'Drizzle': src = 'images/drizzle.png';
			break;
		case 'Mist': src = 'images/mist.png';
			break;
		case 'Haze': src = 'images/haze.png';
			break;
		default:
	}
	$('#weatherIcon').attr("src", src);
	$('#sky').html(jsonData.weather.weather[0].description);

	$('#pressure').html(jsonData.weather.main.pressure + ' mb');
	$('#humidity').html(jsonData.weather.main.humidity + '%');
	let windSpeed = jsonData.weather.wind.speed * 2.23694;
	$('#windSpeed').html(windSpeed.toFixed(2) + ' mph');

}

function populateWeatherHistory() {

	for (let i = jsonData.weatherHistory.length - 1; i >= 0; i--) {
		let today = formatDate(new Date);
		if (today != jsonData.weatherHistory[i].date) {
			let options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
			let dateString = jsonData.weatherHistory[i].date;
			let date = new Date(dateString);
			let formattedDate = date.toLocaleDateString("en-US", options);
			let tempMax = '?';
			if (jsonData.weatherHistory[i].temp_max != null) {
				tempMax = (jsonData.weatherHistory[i].temp_max - 273.15).toFixed(2);
			}
			let tempMin = '?';
			if (jsonData.weatherHistory[i].temp_min != null) {
				tempMin = (jsonData.weatherHistory[i].temp_min - 273.15).toFixed(2);
			}
			let weather = jsonData.weatherHistory[i].weather;
			let description = '';
			if (jsonData.weatherHistory[i].description != null) {
				description = jsonData.weatherHistory[i].description;
			}
			let src = '';
			switch (weather) {
				case 'Clouds': src = 'images/cloudy.png';
					break;
				case 'Rain': src = 'images/rain.png';
					break;
				case 'Clear': src = 'images/sunny.png';
					break;
				case 'Extreme': src = 'images/storm.png';
					break;
				case 'Snow': src = 'images/snowing.png';
					break;
				case 'Drizzle': src = 'images/drizzle.png';
					break;
				case 'Mist': src = 'images/mist.png';
					break;
				case 'Haze': src = 'images/haze.png';
					break;
				default:
			}
			$('#weatherHistory tbody').append(`
				<tr>
					<td>` + formattedDate + `</td>
					<td><img src="` + src + `" class='tableWeatherIcon' /></td>
					<td>` + tempMax + `/` + tempMin + ` C°</td>
					<td>` + description + `</td>
				</tr>
			`);
		}
	}
}

function formatDate(date) {
	var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();

	if (month.length < 2)
		month = '0' + month;
	if (day.length < 2)
		day = '0' + day;

	return [year, month, day].join('-');
}

function populateCurrency() {
	let $baseCurrencyDatalist = $("#baseCurrencyDatalist");
	if ($baseCurrencyDatalist.is(':empty')) {
		$('#baseCurrency').val('USD');
		$('#baseCurrencySymbol').html(' $');
		for (const [key, value] of Object.entries(jsonData.currencies)) {
			let currencySymbol = '';
			if (currency_symbols[key] !== undefined) {
				currencySymbol = currency_symbols[key];
			}
			let option = $("<option />").val(key).text(key + ", " + value + ' ' + currencySymbol);
			$baseCurrencyDatalist.append(option);
			if (key == countryCurrencyCode) {
				$('#countryCurrency2').html(key + ", " + value + ' ' + currencySymbol);
				$('#countryCurrencySymbol').html(' ' + currencySymbol);
			}
		}
	} else {
		$('#baseCurrency').val('USD');
		$('#baseCurrencySymbol').html(' $');
		for (const [key, value] of Object.entries(jsonData.currencies)) {
			let currencySymbol = '';
			if (currency_symbols[key] !== undefined) {
				currencySymbol = currency_symbols[key];
			}
			if (key == countryCurrencyCode) {
				$('#countryCurrency2').html(key + ", " + value + ' ' + currencySymbol);
				$('#countryCurrencySymbol').html(' ' + currencySymbol);
				break;
			}
		}
	}
	updateCurrencyAmount();
}

function updateCurrencyAmount() {
	let baseAmount = $('#baseAmount').val();
	for (const [key, value] of Object.entries(jsonData.exchangeRates.rates)) {
		if (key == countryCurrencyCode) {
			let currencyAmount = baseAmount * value;
			$('#currencyAmount').val(currencyAmount.toFixed(5));
			break;
		}
	}
}

function updateBaseCurrencyAmount() {
	let currencyAmount = $('#currencyAmount').val();
	for (const [key, value] of Object.entries(jsonData.exchangeRates.rates)) {
		if (key == countryCurrencyCode) {
			let newExchangeRate = 1 / value;
			let baseAmount = currencyAmount * newExchangeRate;
			$('#baseAmount').val(baseAmount.toFixed(5));
			break;
		}
	}
}

function updateExchangeRates() {
	let newBaseCurrency = $('#baseCurrency').val();
	let currencySymbol = '';
	if (currency_symbols[newBaseCurrency] !== undefined) {
		currencySymbol = currency_symbols[newBaseCurrency];
	}
	$('#baseCurrencySymbol').html(' ' + currencySymbol);

	const found = Object.entries(jsonData.exchangeRates.rates)
		.find(([key, value]) => key === newBaseCurrency);
	let newBaseExchangeRate = found[1];

	Object.keys(jsonData.exchangeRates.rates).forEach(function (key) {
		let exchangeRate = jsonData.exchangeRates.rates[key] / newBaseExchangeRate;
		jsonData.exchangeRates.rates[key] = exchangeRate;
	});
	updateCurrencyAmount();
}

function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	} else {
		alert('Geolocation is not supported by this browser.');

	}
}

var getPosition = function (options) {
	return new Promise(function (resolve, reject) {
		navigator.geolocation.getCurrentPosition(resolve, reject, options);
	});
}

function showPosition(position) {
	latitude = position.coords.latitude;
	longitude = position.coords.longitude;
	$('#searchBar').val(latitude + ',' + longitude);
	initMap();
	callApi();
	$('#searchBar').val('');
}

function updateMapCenter() {
	mymap.panTo(new L.LatLng(latitude, longitude));
	var newLatLng = new L.LatLng(latitude, longitude);
	marker.setLatLng(newLatLng);
}

function initMap() {
	mymap = L.map('map').setView([latitude, longitude], zoom);
	marker = L.marker([latitude, longitude]).addTo(mymap);

	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		maxZoom: 18,
		id: 'mapbox/streets-v11',
		tileSize: 512,
		zoomOffset: -1,
		accessToken: CONFIG.MAPBOX_ACCESS_TOKEN
	}).addTo(mymap);

	/*L.geoJson(geoJson).addTo(mymap);*/
}

function hideLoader() {
	$('#loading').hide();
}

function showLoader() {
	$('#loading').show();
}

$(document).ready(function () {

	$('#weather').hide();
	$('#currency').hide();
	$('#statistics').hide();
	$('#weatherWarning').hide();
	$('#IntroWarning').hide();
	$('#countryStatsWarning').hide();


	getPosition()
		.then((position) => {
			showPosition(position);
		})
		.catch((err) => {
			console.error(err.message);
		});

	$('#searchBar').change(callApi);
	setInterval(function () {
		geocoderClickCounter = 0;
		enableSearchButton();
	}, 60000);



	$('#showGeneralInfo').click(showGeneralInfo);
	$('#showWeather').click(showWeather);
	$('#showCurrency').click(showCurrency);
	$('#showStats').click(showStats);

	$('#baseAmount').change(updateCurrencyAmount);
	$('#currencyAmount').change(updateBaseCurrencyAmount);
	$('#baseCurrency').change(updateExchangeRates);

});