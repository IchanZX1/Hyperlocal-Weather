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

    public function getWeatherByLocation($kab, $kec, $desa)
    {
        try {
            $weatherData = $this->apiService->getWeatherByLocation($kab, $kec, $desa);
            return $weatherData;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}