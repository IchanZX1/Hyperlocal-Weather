<?php
require_once __DIR__ . '/../classes/Controller.php';
require_once __DIR__ . '/../classes/background-set.php';

$controller = new Controller();

// Default location
$kab = $_GET['kab'] ?? null;
$kec = $_GET['kec'] ?? null;
$desa = $_GET['desa'] ?? null;
$prov = $_GET['prov_name'] ?? null;
$lat = $_GET['lat'] ?? null;
$lon = $_GET['lon'] ?? null;

if ($lat && $lon) {
    $weatherResponse = $controller->getWeatherByCoords($lat, $lon);
    // For display purposes, try to get names if they aren't provided
    if (!$kab) {
        $kab = $weatherResponse['lokasi']['kabupaten'] ?? 'Lokasi';
        $kec = $weatherResponse['lokasi']['kecamatan'] ?? 'Sekitar';
        $desa = $weatherResponse['lokasi']['desa'] ?? 'Anda';
    }
} else {
    // Default to Jember if nothing selected
    $kab = $kab ?? 'Jember';
    $kec = $kec ?? 'Sumbersari';
    $desa = $desa ?? 'Mastrip';
    $prov = $prov ?? 'Jawa Timur';
    $weatherResponse = $controller->getWeatherByLocation($kab, $kec, $desa, $prov);
}



if (is_array($weatherResponse) && isset($weatherResponse['status']) && $weatherResponse['status'] === true) {
    $loc = $weatherResponse['lokasi'];
    $forecasts = $weatherResponse['cuaca'];
    $current = $forecasts[0];

    // Initialize background set logic
    $bgSetter = new BackgroundSet();
    $backgroundUrl = $bgSetter->getBackground($current['hour'], $current['deskripsi']);

    // Map data for template
    $kota = $loc['desa'];
    $wilayah = $loc['kecamatan'] . ', ' . $loc['kabupaten'];
    $suhu = $current['instant']['air_temperature'];
    $terasa = $suhu + 1;
    $kondisi = $current['deskripsi'];
    $terbit = '05:12';
    $terbenam = '17:35';

    $detail = [
        'Kecepatan angin' => $current['instant']['wind_speed'] . ' m/s',
        'Kelembaban' => $current['instant']['relative_humidity'] . '%',
        'Tekanan Udara' => $current['instant']['air_pressure_at_sea_level'] . ' hPa',
        'Peluang Hujan' => $current['peluang_hujan'] . '%',
    ];

    $per_jam = [];
    foreach (array_slice($forecasts, 0, 8) as $f) {
        $per_jam[] = [
            'label' => $f['jam'],
            'ikon' => $f['emoji'],
            'suhu' => $f['instant']['air_temperature'],
            'periode' => getPeriode((int) $f['hour'])
        ];
    }

    $mingguan = [];
    $daysCount = 0;
    $lastDate = '';
    foreach ($forecasts as $f) {
        if ($f['tanggal'] != $lastDate && $daysCount < 7) {
            $dt = new DateTime($f['tanggal']);
            $mingguan[] = [
                'hari' => strtoupper(getHariIndo($dt->format('w'))),
                'tgl' => $dt->format('j') . ' ' . getBulanIndo($dt->format('n')),
                'min' => $f['instant']['air_temperature'] - 2,
                'max' => $f['instant']['air_temperature'] + 3,
                'ikon' => $f['emoji'],
                'label' => $f['deskripsi']
            ];
            $lastDate = $f['tanggal'];
            $daysCount++;
        }
    }
} else {
    $errorMessage = $weatherResponse['error'] ?? $weatherResponse['message'] ?? 'Lokasi tidak ditemukan atau API bermasalah.';
    die("Data tidak tersedia: " . htmlspecialchars($errorMessage));
}

function getPeriode($hour)
{
    if ($hour >= 5 && $hour < 11)
        return 'PAGI';
    if ($hour >= 11 && $hour < 15)
        return 'SIANG';
    if ($hour >= 15 && $hour < 18)
        return 'SORE';
    return 'MALAM';
}

function getHariIndo($w)
{
    $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    return $days[$w];
}

function getBulanIndo($n)
{
    $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
    return $months[$n];
}

$now = new DateTime();
$hari_id = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan_id = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
$hari_str = $hari_id[(int) $now->format('w')];
$tgl_str = $now->format('j');
$bln_str = $bulan_id[(int) $now->format('n')];
$thn_str = $now->format('Y');
$jam_str = $now->format('H:i');
?>
<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/partials/head.php'; ?>

<body>
    <?php include __DIR__ . '/partials/modal.php'; ?>
    <?php include __DIR__ . '/partials/background.php'; ?>

    <div class="app">
        <?php include __DIR__ . '/partials/topbar.php'; ?>

        <div class="mid">
            <?php include __DIR__ . '/partials/current-weather.php'; ?>
            <div class="vdiv"></div>
            <?php include __DIR__ . '/partials/details.php'; ?>
            <div class="vdiv"></div>
            <?php include __DIR__ . '/partials/hourly.php'; ?>
        </div>

        <?php include __DIR__ . '/partials/weekly.php'; ?>
    </div>

    <?php include __DIR__ . '/partials/scripts.php'; ?>
</body>

</html>