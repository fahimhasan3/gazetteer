let geocoderClickCounter = 0;
let jsonData = [];
let countryCurrencyCode;
let latitude = 0;
let longitude = 0;
let zoom = 11;
var mymap;
var marker;

function callApi() {
	let searchValue = $('#searchBar').val();
	if (searchValue) {
		showLoader();
		disableSearchButton();
		$.post('server-php/apiCaller.php', { q: searchValue, lang: "EN" }, function (data) {
			console.log(data);
			let jsonString = JSON.stringify(data);
			jsonData = data;
			updateLatitudeLongitude();
			populateGeneralInfo();
			populateWeather();
			populateWeatherHistory();
			populateCurrency();

			enableSearchButton();
			hideLoader();
		});
		geocoderClickCounter++;
		if (geocoderClickCounter >= 5) {
			disableSearchButton();
		}
	}
}

function disableSearchButton() {
	$("#searchButton").prop('disabled', true);
}
function enableSearchButton() {
	$("#searchButton").prop('disabled', false);
}

function showGeneralInfo() {
	$('#general-info').show();
	$('#weather').hide();
	$('#currency').hide();

	$('#showGeneralInfo').addClass('active');
	$('#showWeather').removeClass('active');
	$('#showCurrency').removeClass('active');
}

function showWeather() {
	$('#general-info').hide();
	$('#showGeneralInfo').removeClass('active');

	$('#weather').show();
	$('#showWeather').addClass('active');

	$('#currency').hide();
	$('#showCurrency').removeClass('active');
}

function showCurrency() {
	$('#general-info').hide();
	$('#weather').hide();
	$('#currency').show();

	$('#showGeneralInfo').removeClass('active');
	$('#showWeather').removeClass('active');
	$('#showCurrency').addClass('active');
}

function populateGeneralInfo() {
	$('#stateDistrict').html("");
	$('#state').html("");

	$('#general-info').html(jsonData.wikiIntro);
	$('#cityNameTitle').html(jsonData.geocode.city);
	$('h2').html(jsonData.geocode.formatted);

	$('#stateDistrict').html(jsonData.geocode.state_district);
	$('#stateName').html(jsonData.geocode.state);
	$('#countryName').html(jsonData.geonames.geonames[0].countryName);
	$('#continentName').html(jsonData.geonames.geonames[0].continentName);

	//currency
	countryCurrencyCode = jsonData.geonames.geonames[0].currencyCode;
	for (const [key, value] of Object.entries(jsonData.currencies)) {
		if (key == countryCurrencyCode) {
			$('#countryCurrency').html(key + ", " + value);
		}
	}
}

function updateLatitudeLongitude() {
	latitude = jsonData.geocode.geometry.lat;
	longitude = jsonData.geocode.geometry.lng;
	updateMapCenter();
}

function populateWeather() {
	let kelvinTemp = jsonData.weather.main.temp;
	let celsiusTemp = kelvinTemp - 273.15;
	$('#temperature').html(celsiusTemp.toFixed(2));

	let sky = jsonData.weather.weather[0].main;
	console.log(sky);
	let src = '';
	switch (sky) {
		case 'Clouds': src = 'images/cloudy.png';
			break;
		case 'Rain': src = 'images/rain.png';
			break;
		case 'Clear': src = 'images/sunny.png';
			break;
		default:
	}
	$('#weatherIcon').attr("src", src);
	$('#sky').html(jsonData.weather.weather[0].description);
}

function populateWeatherHistory() {
	$("#weatherHistory td").remove(); 


	for(let i = jsonData.weatherHistory.length - 1; i >= 0; i--) {
		let today = formatDate(new Date);
		if(today != jsonData.weatherHistory[i].date) {
			let date = jsonData.weatherHistory[i].date;
			let celsiusTemp = jsonData.weatherHistory[i].temperature - 273.15;
			let weather = jsonData.weatherHistory[i].weather;
			let src = '';
			switch (weather) {
				case 'Clouds': src = 'images/cloudy.png';
					break;
				case 'Rain': src = 'images/rain.png';
					break;
				case 'Clear': src = 'images/sunny.png';
					break;
				default:
			}
	
			$('#dateRow').append('<td class="temperatureRow">' + date + '</td>');
			$('#weatherRow').append('<td class="temperatureRow">' + celsiusTemp.toFixed(2) + ' C° <img src="' + src + '" class="tableWeatherIcon" />');
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
	if($baseCurrencyDatalist.is(':empty')) {
		$('#baseCurrency').val('USD');
		for (const [key, value] of Object.entries(jsonData.currencies)) {
			let option = $("<option />").val(key).text(key + ", " + value);
			$baseCurrencyDatalist.append(option);
			if (key == countryCurrencyCode) {
				$('#countryCurrency2').html(key + ", " + value);
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
	console.log(latitude + ',' + longitude);
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
		accessToken: 'pk.eyJ1IjoiZmFoaW0zIiwiYSI6ImNrZDdnMmszbTBqdW4yeHNjd2c2MmVwMzIifQ.OjopazdBVPNFr8szxd_jcQ'
	}).addTo(mymap);
}

function hideLoader() {
    $('#loading').hide();
}

function showLoader() {
	$('#loading').show();
}

$(document).ready(function () {

	getPosition()
		.then((position) => {
			showPosition(position);
		})
		.catch((err) => {
			console.error(err.message);
		});

	$('#searchButton').click(callApi);
	setInterval(function () {
		geocoderClickCounter = 0;
		enableSearchButton();
	}, 60000);

	$('#weather').hide();
	$('#currency').hide();

	$('#showGeneralInfo').click(showGeneralInfo);
	$('#showWeather').click(showWeather);
	$('#showCurrency').click(showCurrency);

	$('#baseAmount').change(updateCurrencyAmount);
	$('#currencyAmount').change(updateBaseCurrencyAmount);
	$('#baseCurrency').change(updateExchangeRates);

});