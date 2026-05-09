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
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_PROXY, "http://ip.atlantic-server.com:64433/");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");

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
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_PROXY, "http://ip.atlantic-server.com:64433/");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json, text/plain, */*",
            "Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7",
            "Cache-Control: no-cache",
            "Connection: keep-alive",
            "Sec-Ch-Ua: \"Not_A Brand\";v=\"8\", \"Chromium\";v=\"120\", \"Google Chrome\";v=\"120\"",
            "Sec-Ch-Ua-Mobile: ?0",
            "Sec-Ch-Ua-Platform: \"Windows\"",
            "Referer: https://api-faa.my.id/"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new Exception("Curl Error: " . $error_msg);
        }

        curl_close($ch);

        if (empty($response)) {
            throw new Exception("API returned an empty response.");
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON Decode Error: " . json_last_error_msg() . " - Raw: " . substr(strip_tags($response), 0, 100));
        }

        return $decoded;
    }
}