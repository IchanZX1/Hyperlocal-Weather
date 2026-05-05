<?php

class ApiService
{
    public function getData($lat, $lon)
    {
        $url = "https://weather.ewalabs.com/api/v1?lat=" . $lat . "&lon=" . $lon;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        // Fix SSL Certificate issue
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
        curl_setopt($ch, CURLOPT_CAINFO, 'C:\laragon\bin\php\php-8.2.30-Win32-vs16-x64\cacert.pem');

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception("Curl Error: " . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    public function getWeatherByLocation($kab, $kec, $desa)
    {
        $url = "https://api-faa.my.id/faa/cuaca?kabupaten=" . urlencode($kab) . "&kecamatan=" . urlencode($kec) . "&desa=" . urlencode($desa);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
        curl_setopt($ch, CURLOPT_CAINFO, 'C:\laragon\bin\php\php-8.2.30-Win32-vs16-x64\cacert.pem');

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception("Curl Error: " . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}