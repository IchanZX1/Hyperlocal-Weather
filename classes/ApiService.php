<?php

class ApiService
{
    private $cacheFile;

    public function __construct()
    {
        $this->cacheFile = __DIR__ . '/wilayah_cache.json';
        date_default_timezone_set('Asia/Jakarta');
    }


    private function fetchUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Curl Error: " . $error);
        }

        return $response;
    }

    private function getCache($key)
    {
        if (!file_exists($this->cacheFile)) return null;
        $cache = json_decode(file_get_contents($this->cacheFile), true);
        return $cache[$key] ?? null;
    }

    private function saveCache($key, $data)
    {
        $cache = file_exists($this->cacheFile) ? json_decode(file_get_contents($this->cacheFile), true) : [];
        $cache[$key] = $data;
        file_put_contents($this->cacheFile, json_encode($cache, JSON_PRETTY_PRINT));
    }

    public function getCoordinates($kab, $kec, $desa, $prov = '')
    {
        $cacheKey = md5(strtolower("$desa|$kec|$kab|$prov"));
        $cached = $this->getCache($cacheKey);
        if ($cached) return $cached;

        $addressParts = array_filter([$desa, $kec, $kab, $prov, 'Indonesia']);
        $query = urlencode(implode(', ', $addressParts));
        $url = "https://nominatim.openstreetmap.org/search?format=json&q=$query&limit=1";
        
        $response = $this->fetchUrl($url);
        $data = json_decode($response, true);

        if (empty($data)) {
            $addressParts = array_filter([$kec, $kab, $prov, 'Indonesia']);
            $query = urlencode(implode(', ', $addressParts));
            $url = "https://nominatim.openstreetmap.org/search?format=json&q=$query&limit=1";
            $response = $this->fetchUrl($url);
            $data = json_decode($response, true);
        }

        if (empty($data)) {
            throw new Exception("Lokasi tidak ditemukan di peta.");
        }

        $result = ['lat' => $data[0]['lat'], 'lon' => $data[0]['lon']];
        $this->saveCache($cacheKey, $result);
        return $result;
    }

    public function getWeatherByCoords($lat, $lon)
    {
        try {
            $locationNames = $this->reverseGeocode($lat, $lon);
            return $this->processWeatherData($lat, $lon, $locationNames['kab'], $locationNames['kec'], $locationNames['desa']);
        } catch (Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    private function reverseGeocode($lat, $lon)
    {
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}&addressdetails=1";
        $response = $this->fetchUrl($url);
        $data = json_decode($response, true);

        $address = $data['address'] ?? [];
        return [
            'desa' => $address['village'] ?? $address['suburb'] ?? $address['neighbourhood'] ?? 'Anda',
            'kec' => $address['city_district'] ?? $address['district'] ?? 'Sekitar',
            'kab' => $address['city'] ?? $address['regency'] ?? $address['county'] ?? 'Lokasi'
        ];
    }


    public function getWeatherByLocation($kab, $kec, $desa, $prov = '')
    {
        try {
            $coords = $this->getCoordinates($kab, $kec, $desa, $prov);
            return $this->processWeatherData($coords['lat'], $coords['lon'], $kab, $kec, $desa, $prov);
        } catch (Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    private function processWeatherData($lat, $lon, $kab, $kec, $desa, $prov = '')
    {
        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&hourly=temperature_2m,relative_humidity_2m,precipitation_probability,weather_code,wind_speed_10m,surface_pressure&timezone=Asia%2FJakarta&forecast_days=7";
        
        $response = $this->fetchUrl($url);
        $data = json_decode($response, true);

        if (!isset($data['hourly'])) {
            throw new Exception("Gagal mengambil data cuaca dari Open-Meteo.");
        }

        $cuaca = [];
        $currentHour = (int)date('H');
        $currentDate = date('Y-m-d');

        foreach ($data['hourly']['time'] as $i => $time) {
            $dt = new DateTime($time);
            $forecastDate = $dt->format('Y-m-d');
            $forecastHour = (int)$dt->format('H');

            if ($forecastDate === $currentDate && $forecastHour < $currentHour) {
                continue;
            }

            $code = $data['hourly']['weather_code'][$i];
            $interpret = $this->interpretWmoCode($code);

            $cuaca[] = [
                'hour' => $dt->format('H'),
                'jam' => $dt->format('H:i'),
                'tanggal' => $dt->format('Y-m-d'),
                'time_local' => $dt->format('H:i') . ' WIB',
                'deskripsi' => $interpret['desc'],
                'emoji' => $interpret['emoji'],
                'peluang_hujan' => $data['hourly']['precipitation_probability'][$i],
                'instant' => [
                    'air_temperature' => $data['hourly']['temperature_2m'][$i],
                    'relative_humidity' => $data['hourly']['relative_humidity_2m'][$i],
                    'wind_speed' => $data['hourly']['wind_speed_10m'][$i],
                    'air_pressure_at_sea_level' => $data['hourly']['surface_pressure'][$i]
                ]
            ];
        }

        $firstForecast = $cuaca[0] ?? null;
        $prediksi_harian = [
            'ringkas' => $firstForecast ? "Hari ini diprediksi " . strtolower($firstForecast['deskripsi']) : "Data tidak tersedia",
            'detail' => $firstForecast ? "Suhu rata-rata " . $firstForecast['instant']['air_temperature'] . "°C dengan peluang hujan " . $firstForecast['peluang_hujan'] . "%." : ""
        ];

        return [
            'status' => true,
            'lokasi' => [
                'desa' => $desa,
                'kecamatan' => $kec,
                'kabupaten' => $kab,
                'provinsi' => $prov
            ],
            'cuaca' => array_values($cuaca),
            'prediksi_harian' => $prediksi_harian
        ];
    }

    private function interpretWmoCode($code) {
        $map = [
            0 => ['desc' => 'Cerah', 'emoji' => '☀️'],
            1 => ['desc' => 'Cerah Berawan', 'emoji' => '🌤️'],
            2 => ['desc' => 'Berawan', 'emoji' => '⛅'],
            3 => ['desc' => 'Mendung', 'emoji' => '☁️'],
            45 => ['desc' => 'Kabut', 'emoji' => '🌫️'],
            48 => ['desc' => 'Kabut Berembun', 'emoji' => '🌫️'],
            51 => ['desc' => 'Gerimis Ringan', 'emoji' => '🌦️'],
            53 => ['desc' => 'Gerimis', 'emoji' => '🌦️'],
            55 => ['desc' => 'Gerimis Lebat', 'emoji' => '🌦️'],
            61 => ['desc' => 'Hujan Ringan', 'emoji' => '🌧️'],
            63 => ['desc' => 'Hujan', 'emoji' => '🌧️'],
            65 => ['desc' => 'Hujan Lebat', 'emoji' => '🌧️'],
            71 => ['desc' => 'Salju Ringan', 'emoji' => '❄️'],
            80 => ['desc' => 'Hujan Showers', 'emoji' => '🌦️'],
            95 => ['desc' => 'Badai Petir', 'emoji' => '⛈️'],
        ];
        return $map[$code] ?? ['desc' => 'Berawan', 'emoji' => '☁️'];
    }
}