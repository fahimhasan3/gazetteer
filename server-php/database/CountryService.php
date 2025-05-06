<?php
class CountryService
{
    public $db;
    function __construct($db) {
        $this->db = $db;
    }

    public function insertRow($name, $continent, $population, $capital, $currency)
    {
        $id = $this->db->query('INSERT INTO country (name, continent, population, capital, currency) VALUES (?,?,?,?,?)' , $name, $continent, $population, $capital, $currency)->lastInsertID();
        return $id;
    }

    public function getByName($name)
    {
        $result = $this->db->query('SELECT * FROM country WHERE name = ?', $name)->fetchOne();
        return $result;
    }
}
