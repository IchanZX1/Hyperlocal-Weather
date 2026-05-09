<?php
require_once 'ApiService.php';

class Controller
{
    protected $apiService;

    public function __construct()
    {
        $this->apiService = new ApiService();
    }

    public function getWeather($latitude, $longitude)
    {
        try {
            $weatherData = $this->apiService->getData($latitude, $longitude);
            return $weatherData;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getWeatherByLocation($kab, $kec, $desa, $prov = '')
    {
        try {
            $weatherData = $this->apiService->getWeatherByLocation($kab, $kec, $desa, $prov);
            return $weatherData;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getWeatherByCoords($lat, $lon)
    {
        try {
            $weatherData = $this->apiService->getWeatherByCoords($lat, $lon);
            return $weatherData;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }


}