<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gazetteer</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    <script src="javascript/jquery-3.5.1.min.js"></script>


    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <script src="javascript/index.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>

<body>
    <div id="loading"></div>
    <nav>
        <ul>
            <li>
                <input type="text" name="searchbar" id="searchBar" class='form-control' placeholder="city, country" autocomplete="on" required />
            </li>
            <li>
                <input type="submit" id="searchButton" name="searchButton" value="Search" class='btn btn-primary' />
            </li>
            <li class='active' id='showGeneralInfo'>Intro</li>
            <li id='showStats'>Country Stats</li>
            <li id='showWeather'>Weather</li>
            <li id='showCurrency'>Currency</li>
        </ul>

    </nav>
    <div id="map">

    </div>
    <footer>
        <section id='cityNameSection'>
            <h1 id='cityNameTitle'></h1>
            <h2 id='cityFormattedName'></h2>
        </section>

        <section id="content">

            <section id='general-info'>
                <section id='wikiIntro'>

                </section>
                <div id='IntroWarning' class="alert alert-warning" role="alert">
                    Wikipedia information not found.
                </div>
            </section>

            <section id='statistics'>
                <table class="table table-bordered" id='countryStatsTable'>
                    <tr>
                        <th>Flag</th>
                        <td colspan="3"><img src='' id='countryStatsFlag' alt='country flag' /></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td id='countryStatsName'></td>
                        <th>Alpha2Code</th>
                        <td id='countryStatsAlpha2Code'></td>
                    </tr>
                    <tr>
                        <th>Capital</th>
                        <td id='countryStatsCapital'></td>
                        <th>Alpha3Code</th>
                        <td id='countryStatsAlpha3Code'></td>
                    </tr>
                    <tr>
                        <th>Region</th>
                        <td id='countryStatsRegion'></td>
                        <th>Languages</th>
                        <td id='countryStatsLanguages'></td>
                    </tr>
                    <tr>
                        <th>Subregion</th>
                        <td id='countryStatsSubregion'></td>
                        <th>Currencies</th>
                        <td id='countryStatsCurrencies'></td>
                    </tr>
                    <tr>
                        <th>Population</th>
                        <td id='countryStatsPopulation'></td>
                        <th>Calling Codes</th>
                        <td id='countryStatsCallingCodes'></td>
                    </tr>
                    <tr>
                        <th>Demonym</th>
                        <td id='countryStatsDemonym'></td>
                    </tr>
                    <tr>
                        <th>Area</th>
                        <td id='countryStatsArea'></td>
                    </tr>
                </table>
                <div id='countryStatsWarning' class="alert alert-warning" role="alert">
                    Country stats information not found.
                </div>
            </section>
            <section id="weather">
                <section id='weatherTableContainer'>
                    <table id='weatherTable' class='table table-bordered'>
                        <tr>
                            <td colspan="3" id='todayDate'>07 september 2020</td>
                        </tr>
                        <tr>
                            <td colspan="3" id='sky'>clear sky</td>
                        </tr>
                        <tr>
                            <td id='currentWeatherColumn'><img src="images/sunny.png" alt="weather icon" id='weatherIcon' /><span id="temperature"></span></td>
                            <td>
                                <table class='table table-borderless'>
                                    <tr>
                                        <td>Pressure</td>
                                        <td id='pressure'>N/A</td>
                                    </tr>
                                    <tr>
                                        <td>Humidity</td>
                                        <td id='humidity'>N/A</td>
                                    </tr>
                                    <tr>
                                        <td>Wind Speed</td>
                                        <td id='windSpeed'>N/A</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    </table>
                    <p>Past days weather</p>
                    <table id='weatherHistory' class='table table-bordered'>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Weather</th>
                                <th>Temperature max/min</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </section>
                <div id='weatherWarning' class="alert alert-warning" role="alert">
                    Weather information not found.
                </div>
            </section>
            <section id="currency">
                <div id='currencyDiv'>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>Base Currency</th>
                                <th>Country's currency</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> <input list="baseCurrencyDatalist" name="baseCurrency" id="baseCurrency" class='form-control'>
                                    <datalist id='baseCurrencyDatalist'></datalist>
                                </td>
                                <td> <span id='countryCurrency2'></span></td>
                            </tr>
                            <tr>
                                <th><label for="baseAmount">Amount: </label> </th>
                                <th><label for="currencyAmount">Amount: </label></th>
                            </tr>
                            <tr>
                                <td><input type='number' name='baseAmount' value='1' id='baseAmount' class='form-control' required /><span id='baseCurrencySymbol'></span></td>
                                <td><input type='number' name='currencyAmount' id='currencyAmount' class='form-control' /><span id='countryCurrencySymbol'></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <div id='cityStats'>
                <img id='countryFlag' src='' alt='country flag' />
                <table>
                    <tr>
                        <th>District</th>
                        <td id='stateDistrict'></td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td id='stateName'></td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td id='countryName'></td>
                    </tr>
                    <tr>
                        <th>Continent</th>
                        <td id='continentName'></td>
                    </tr>
                    <tr>
                        <th>Currency</th>
                        <td id='countryCurrency'></td>
                    </tr>
                </table>
            </div>

        </section>

    </footer>
</body>

</html>