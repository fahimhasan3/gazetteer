<?php
class WeatherService
{
    public $db;
    function __construct($db) {
        $this->db = $db;
    }

    public function insertRow($cityId, $weather, $temperature, $date)
    {
        $id = $this->db->query('INSERT INTO weather (city_id, weather, temperature, date) VALUES (?,?,?,?)' , $cityId, $weather, $temperature, $date )->lastInsertID();
        return $id;
    }

    public function getLast6WeatherByCity($cityId)
    {
        $results = $this->db->query("SELECT * FROM weather WHERE city_id = ? ORDER BY DATE DESC LIMIT 6", $cityId)->fetchAll();
        return $results;
    }

    public function getWeatherByCityAndDate($cityId, $date) {
        $result = $this->db->query("SELECT * FROM weather WHERE city_id = ? and date = ?", $cityId, $date)->fetchArray();
        return $result;
    }
}
