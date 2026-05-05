<?php

class BackgroundSet
{
    private $backgrounds = [];

    public function __construct()
    {
        $this->loadEnv();
    }

    /**
     * Sederhana .env loader untuk mengambil data dari file .env
     */
    private function loadEnv()
    {
        $envPath = __DIR__ . '/../.env';
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Lewati komentar
                if (strpos(trim($line), '#') === 0) continue;
                
                // Pisahkan key dan value
                if (strpos($line, '=') !== false) {
                    list($name, $value) = explode('=', $line, 2);
                    $this->backgrounds[trim($name)] = trim($value);
                }
            }
        }
    }

    /**
     * Mendapatkan URL background berdasarkan jam dan deskripsi cuaca
     */
    public function getBackground($hour, $condition)
    {
        $periode = $this->getPeriodeName($hour);
        $cuaca = $this->getCuacaType($condition);
        
        $key = "{$periode}_{$cuaca}";
        
        // Kembalikan URL dari .env jika ada, atau default pagi cerah
        return $this->backgrounds[$key] ?? $this->backgrounds['PAGI_CERAH'] ?? '';
    }

    /**
     * Logika pembagian waktu (PAGI, SIANG, SORE, MALAM)
     */
    private function getPeriodeName($hour)
    {
        $hour = (int)$hour;
        if ($hour >= 5 && $hour < 11) return 'PAGI';
        if ($hour >= 11 && $hour < 15) return 'SIANG';
        if ($hour >= 15 && $hour < 18) return 'SORE';
        return 'MALAM';
    }

    /**
     * Logika pemetaan kondisi cuaca (CERAH, MENDUNG, HUJAN)
     */
    private function getCuacaType($condition)
    {
        $condition = strtolower($condition);
        
        if (strpos($condition, 'hujan') !== false || strpos($condition, 'rain') !== false) {
            return 'HUJAN';
        }
        
        if (
            strpos($condition, 'mendung') !== false || 
            strpos($condition, 'awan') !== false || 
            strpos($condition, 'cloudy') !== false || 
            strpos($condition, 'overcast') !== false
        ) {
            return 'MENDUNG';
        }
        
        return 'CERAH';
    }
}
