<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gazetteer</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
        integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
        crossorigin=""></script>
    <script src="javascript/jquery-3.5.1.min.js"></script>
    <script src="javascript/index.js"></script>
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>

<body>
    <div id="loading"></div>
    <nav>
        <input type="text" name="searchbar" id="searchBar" placeholder="city, country" autocomplete="on" required />
        <input type="submit" id="searchButton" name="searchButton" value="Search" />
        <ul>
            <li class='active' id='showGeneralInfo'>General Info</li>
            <li id='showWeather'>Weather</li>
            <li id='showCurrency'>Currency</li>
        </ul>
    </nav>
    <div id="map">

    </div>
    <footer>
        <h1 id='cityNameTitle'></h1>
        <h2></h2>
        <section id="content">

            <section id='general-info'></section>
            <section id="weather">
                <p>Today: <span id="temperature"></span> C° <img src="images/sunny.png" alt="weather icon"
                        id='weatherIcon' /></p>
                <p id='sky'></p>
                <table id='weatherHistory'>
                    <tr id='dateRow'>
                        <th>Date</th>
                    </tr>
                    <tr id='weatherRow'>
                        <th>Weather</th>
                    </tr>
                </table>
            </section>
            <section id="currency">
                <div id='currencyDiv'>
                    <table>
                        <thead>
                            <tr>
                                <td>Base Currency</td>
                                <td>Country's currency</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> <input list="baseCurrencyDatalist" name="baseCurrency" id="baseCurrency">
                                    <datalist id='baseCurrencyDatalist'></datalist>
                                </td>
                                <td> <span id='countryCurrency2'></span></td>
                            </tr>
                            <tr>
                                <td><label for="baseAmount">Amount: </label> </td>
                                <td><label for="currencyAmount">Amount: </label></td>
                            </tr>
                            <tr>
                                <td><input type='number' name='baseAmount' value='1' id='baseAmount' required /></td>
                                <td><input type='number' name='currencyAmount' id='currencyAmount' /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <div id='cityStats'>
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