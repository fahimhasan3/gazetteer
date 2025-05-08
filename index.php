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

    <!-- <script src="javascript/config.js"></script> -->
    <script>
        const CONFIG = {
            MAPBOX_ACCESS_TOKEN: "<?= getenv('MAPBOX_ACCESS_TOKEN') ?>"
        };
    </script>
    <script src="javascript/index.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>

<body>
    <div id="loading"></div>
    <nav>
        <ul>
            <li>
                <input list="countryDatalist" name="searchbar" id="searchBar" class='form-control' placeholder="country"  />
                <datalist id="countryDatalist">
                    <option value="Afghanistan" />
                    <option value="Albania" />
                    <option value="Algeria" />
                    <option value="American Samoa" />
                    <option value="Andorra" />
                    <option value="Angola" />
                    <option value="Anguilla" />
                    <option value="Antarctica" />
                    <option value="Antigua and Barbuda" />
                    <option value="Argentina" />
                    <option value="Armenia" />
                    <option value="Aruba" />
                    <option value="Australia" />
                    <option value="Austria" />
                    <option value="Azerbaijan" />
                    <option value="Bahamas" />
                    <option value="Bahrain" />
                    <option value="Bangladesh" />
                    <option value="Barbados" />
                    <option value="Belarus" />
                    <option value="Belgium" />
                    <option value="Belize" />
                    <option value="Benin" />
                    <option value="Bermuda" />
                    <option value="Bhutan" />
                    <option value="Bolivia" />
                    <option value="Bosnia and Herzegovina" />
                    <option value="Botswana" />
                    <option value="Bouvet Island" />
                    <option value="Brazil" />
                    <option value="British Indian Ocean Territory" />
                    <option value="Brunei Darussalam" />
                    <option value="Bulgaria" />
                    <option value="Burkina Faso" />
                    <option value="Burundi" />
                    <option value="Cambodia" />
                    <option value="Cameroon" />
                    <option value="Canada" />
                    <option value="Cape Verde" />
                    <option value="Cayman Islands" />
                    <option value="Central African Republic" />
                    <option value="Chad" />
                    <option value="Chile" />
                    <option value="China" />
                    <option value="Christmas Island" />
                    <option value="Cocos (Keeling) Islands" />
                    <option value="Colombia" />
                    <option value="Comoros" />
                    <option value="Congo" />
                    <option value="Congo, The Democratic Republic of The" />
                    <option value="Cook Islands" />
                    <option value="Costa Rica" />
                    <option value="Cote D'ivoire" />
                    <option value="Croatia" />
                    <option value="Cuba" />
                    <option value="Cyprus" />
                    <option value="Czech Republic" />
                    <option value="Denmark" />
                    <option value="Djibouti" />
                    <option value="Dominica" />
                    <option value="Dominican Republic" />
                    <option value="Ecuador" />
                    <option value="Egypt" />
                    <option value="El Salvador" />
                    <option value="Equatorial Guinea" />
                    <option value="Eritrea" />
                    <option value="Estonia" />
                    <option value="Ethiopia" />
                    <option value="Falkland Islands (Malvinas)" />
                    <option value="Faroe Islands" />
                    <option value="Fiji" />
                    <option value="Finland" />
                    <option value="France" />
                    <option value="French Guiana" />
                    <option value="French Polynesia" />
                    <option value="French Southern Territories" />
                    <option value="Gabon" />
                    <option value="Gambia" />
                    <option value="Georgia" />
                    <option value="Germany" />
                    <option value="Ghana" />
                    <option value="Gibraltar" />
                    <option value="Greece" />
                    <option value="Greenland" />
                    <option value="Grenada" />
                    <option value="Guadeloupe" />
                    <option value="Guam" />
                    <option value="Guatemala" />
                    <option value="Guinea" />
                    <option value="Guinea-bissau" />
                    <option value="Guyana" />
                    <option value="Haiti" />
                    <option value="Heard Island and Mcdonald Islands" />
                    <option value="Holy See (Vatican City State)" />
                    <option value="Honduras" />
                    <option value="Hong Kong" />
                    <option value="Hungary" />
                    <option value="Iceland" />
                    <option value="India" />
                    <option value="Indonesia" />
                    <option value="Iran, Islamic Republic of" />
                    <option value="Iraq" />
                    <option value="Ireland" />
                    <option value="Israel" />
                    <option value="Italy" />
                    <option value="Jamaica" />
                    <option value="Japan" />
                    <option value="Jordan" />
                    <option value="Kazakhstan" />
                    <option value="Kenya" />
                    <option value="Kiribati" />
                    <option value="Korea, Democratic People's Republic of" />
                    <option value="Korea, Republic of" />
                    <option value="Kuwait" />
                    <option value="Kyrgyzstan" />
                    <option value="Lao People's Democratic Republic" />
                    <option value="Latvia" />
                    <option value="Lebanon" />
                    <option value="Lesotho" />
                    <option value="Liberia" />
                    <option value="Libyan Arab Jamahiriya" />
                    <option value="Liechtenstein" />
                    <option value="Lithuania" />
                    <option value="Luxembourg" />
                    <option value="Macao" />
                    <option value="Macedonia, The Former Yugoslav Republic of" />
                    <option value="Madagascar" />
                    <option value="Malawi" />
                    <option value="Malaysia" />
                    <option value="Maldives" />
                    <option value="Mali" />
                    <option value="Malta" />
                    <option value="Marshall Islands" />
                    <option value="Martinique" />
                    <option value="Mauritania" />
                    <option value="Mauritius" />
                    <option value="Mayotte" />
                    <option value="Mexico" />
                    <option value="Micronesia, Federated States of" />
                    <option value="Moldova, Republic of" />
                    <option value="Monaco" />
                    <option value="Mongolia" />
                    <option value="Montserrat" />
                    <option value="Morocco" />
                    <option value="Mozambique" />
                    <option value="Myanmar" />
                    <option value="Namibia" />
                    <option value="Nauru" />
                    <option value="Nepal" />
                    <option value="Netherlands" />
                    <option value="Netherlands Antilles" />
                    <option value="New Caledonia" />
                    <option value="New Zealand" />
                    <option value="Nicaragua" />
                    <option value="Niger" />
                    <option value="Nigeria" />
                    <option value="Niue" />
                    <option value="Norfolk Island" />
                    <option value="Northern Mariana Islands" />
                    <option value="Norway" />
                    <option value="Oman" />
                    <option value="Pakistan" />
                    <option value="Palau" />
                    <option value="Palestinian Territory, Occupied" />
                    <option value="Panama" />
                    <option value="Papua New Guinea" />
                    <option value="Paraguay" />
                    <option value="Peru" />
                    <option value="Philippines" />
                    <option value="Pitcairn" />
                    <option value="Poland" />
                    <option value="Portugal" />
                    <option value="Puerto Rico" />
                    <option value="Qatar" />
                    <option value="Reunion" />
                    <option value="Romania" />
                    <option value="Russian Federation" />
                    <option value="Rwanda" />
                    <option value="Saint Helena" />
                    <option value="Saint Kitts and Nevis" />
                    <option value="Saint Lucia" />
                    <option value="Saint Pierre and Miquelon" />
                    <option value="Saint Vincent and The Grenadines" />
                    <option value="Samoa" />
                    <option value="San Marino" />
                    <option value="Sao Tome and Principe" />
                    <option value="Saudi Arabia" />
                    <option value="Senegal" />
                    <option value="Serbia and Montenegro" />
                    <option value="Seychelles" />
                    <option value="Sierra Leone" />
                    <option value="Singapore" />
                    <option value="Slovakia" />
                    <option value="Slovenia" />
                    <option value="Solomon Islands" />
                    <option value="Somalia" />
                    <option value="South Africa" />
                    <option value="South Georgia and The South Sandwich Islands" />
                    <option value="Spain" />
                    <option value="Sri Lanka" />
                    <option value="Sudan" />
                    <option value="Suriname" />
                    <option value="Svalbard and Jan Mayen" />
                    <option value="Swaziland" />
                    <option value="Sweden" />
                    <option value="Switzerland" />
                    <option value="Syrian Arab Republic" />
                    <option value="Taiwan, Province of China" />
                    <option value="Tajikistan" />
                    <option value="Tanzania, United Republic of" />
                    <option value="Thailand" />
                    <option value="Timor-leste" />
                    <option value="Togo" />
                    <option value="Tokelau" />
                    <option value="Tonga" />
                    <option value="Trinidad and Tobago" />
                    <option value="Tunisia" />
                    <option value="Turkey" />
                    <option value="Turkmenistan" />
                    <option value="Turks and Caicos Islands" />
                    <option value="Tuvalu" />
                    <option value="Uganda" />
                    <option value="Ukraine" />
                    <option value="United Arab Emirates" />
                    <option value="United Kingdom" />
                    <option value="United States" />
                    <option value="United States Minor Outlying Islands" />
                    <option value="Uruguay" />
                    <option value="Uzbekistan" />
                    <option value="Vanuatu" />
                    <option value="Venezuela" />
                    <option value="Viet Nam" />
                    <option value="Virgin Islands, British" />
                    <option value="Virgin Islands, U.S" />
                    <option value="Wallis and Futuna" />
                    <option value="Western Sahara" />
                    <option value="Yemen" />
                    <option value="Zambia" />
                    <option value="Zimbabwe" />
                </datalist>
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
            <h1 id='countryNameTitle'></h1>
            <h2 id='capitalNameFormatted'></h2>
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
                <table class="table table-bordered shadow" id='countryStatsTable'>
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
                    <table id='weatherTable' class='table table-bordered shadow'>
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
                    <table id='weatherHistory' class='table table-bordered shadow'>
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
                    <table class='table shadow'>
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
                        <th>Capital</th>
                        <td id='capital'></td>
                    </tr>
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