<?php
class CityService
{
    public $db;
    function __construct($db) {
        $this->db = $db;
    }

    public function insertRow($name, $countryId, $district, $state)
    {
        $id = $this->db->query('INSERT INTO city (name, country_id, district, state) VALUES (?,?,?,?)' , $name, $countryId, $district, $state)->lastInsertID();
        return $id;
    }

    public function getByNameAndCountry($cityName, $countryId)
    {
        $result = $this->db->query("SELECT * FROM city WHERE name = ? and country_id = ?", $cityName, $countryId)->fetchArray();
        return $result;
    }
}
