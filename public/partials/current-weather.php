<div class="weather-main">
    <div class="wi-big"><?= $current['emoji'] ?></div>
    <div style="font-size: 0.8rem; opacity: 0.6; text-transform: uppercase;">
        <?= $current['time_local'] ?> - <?= date('d M Y') ?>
    </div>
    <div class="w-temp"><?= $suhu ?>°</div>
    <div class="w-cond"><?= $kondisi ?></div>
    <div style="margin-top: 15px; font-size: 0.9rem; opacity: 0.8; line-height: 1.6;">
        <?= $weatherResponse['prediksi_harian']['ringkas'] ?><br>
        <small style="opacity: 0.6;"><?= $weatherResponse['prediksi_harian']['detail'] ?></small>
    </div>
</div>