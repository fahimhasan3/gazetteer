<?php
    include(__DIR__ . '/../OpenExchangeRatesClient.php');

    //Currency api call
    $exchangeRatesClient = new OpenExchangeRatesClient;
    $latestCurrency = $exchangeRatesClient->getLatest();
    $allCurrencies = $exchangeRatesClient->getCurrencies();

    echo 'latestCurrency ' . print_r($latestCurrency);
    // echo 'allCurrencies ' . print_r($allCurrencies); 
?>