<?php
/* ══════════════════════════════════════════
   DATA CUACA — ganti dengan API sesungguhnya
   ══════════════════════════════════════════ */
$kota = 'Jember';
$wilayah = 'Jawa Timur';
$suhu = 29;
$terasa = 31;
$kondisi = 'Berawan sebagian. Tidak ada hujan.';
$terbit = '05:24';
$terbenam = '17:48';

$detail = [
    'Kecepatan angin' => '12–18 m/s',
    'Kelembaban udara' => '74–82%',
    'Tekanan' => '1010–1013 mb',
    'Peluang presipitasi' => '35%',
];

$per_jam = [
    ['label' => '00:00', 'ikon' => '🌙', 'suhu' => 24, 'periode' => 'MALAM'],
    ['label' => '03:00', 'ikon' => '🌙', 'suhu' => 23, 'periode' => 'MALAM'],
    ['label' => '06:00', 'ikon' => '🌤️', 'suhu' => 25, 'periode' => 'PAGI'],
    ['label' => '09:00', 'ikon' => '⛅', 'suhu' => 28, 'periode' => 'PAGI'],
    ['label' => '12:00', 'ikon' => '☀️', 'suhu' => 32, 'periode' => 'SIANG'],
    ['label' => '15:00', 'ikon' => '⛅', 'suhu' => 29, 'periode' => 'SIANG'],
    ['label' => '18:00', 'ikon' => '🌦️', 'suhu' => 27, 'periode' => 'SORE'],
    ['label' => '21:00', 'ikon' => '🌙', 'suhu' => 25, 'periode' => 'MALAM'],
];

$mingguan = [
    ['hari' => 'SELASA', 'tgl' => '6 Mei', 'min' => 24, 'max' => 33, 'ikon' => '⛅', 'label' => 'Berawan sebagian'],
    ['hari' => 'RABU', 'tgl' => '7 Mei', 'min' => 22, 'max' => 28, 'ikon' => '🌧️', 'label' => 'Hujan ringan'],
    ['hari' => 'KAMIS', 'tgl' => '8 Mei', 'min' => 21, 'max' => 27, 'ikon' => '⛈️', 'label' => 'Berawan, badai'],
    ['hari' => 'JUMAT', 'tgl' => '9 Mei', 'min' => 24, 'max' => 35, 'ikon' => '☀️', 'label' => 'Cerah'],
    ['hari' => 'SABTU', 'tgl' => '10 Mei', 'min' => 23, 'max' => 32, 'ikon' => '🌤️', 'label' => 'Sebagian cerah'],
    ['hari' => 'MINGGU', 'tgl' => '11 Mei', 'min' => 25, 'max' => 34, 'ikon' => '☀️', 'label' => 'Cerah'],
    ['hari' => 'SENIN', 'tgl' => '12 Mei', 'min' => 23, 'max' => 31, 'ikon' => '🌦️', 'label' => 'Hujan lokal'],
];

$hari_id = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan_id = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
$now = new DateTime();
$hari_str = $hari_id[(int) $now->format('w')];
$tgl_str = $now->format('j');
$bln_str = $bulan_id[(int) $now->format('n')];
$thn_str = $now->format('Y');
$jam_str = $now->format('H:i');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Synoptic —
        <?= htmlspecialchars($kota) ?>
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden
        }

        body {
            font-family: 'Barlow', sans-serif;
            background: #12090302;
            color: #fff;
        }

        /* ── Background panorama ── */
        .bg {
            position: fixed;
            inset: 0;
            z-index: 0
        }

        .bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.50);
        }

        .bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom,
                    rgba(0, 0, 0, .05) 0%,
                    transparent 40%,
                    rgba(5, 3, 1, .45) 68%,
                    rgba(5, 3, 1, .80) 100%);
        }

        /* ── Wrapper ── */
        .app {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* ══ TOPBAR ══ */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px 0;
            flex-shrink: 0;
        }

        .logo {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .22em;
            text-transform: uppercase;
        }

        .loc-pill {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: .8rem;
            color: rgba(255, 255, 255, .7);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 24px;
            padding: 5px 16px;
            cursor: pointer;
            transition: background .2s;
        }

        .loc-pill:hover {
            background: rgba(255, 255, 255, .07)
        }

        .loc-pill b {
            color: #fff
        }

        .right-nav {
            display: flex;
            align-items: center;
            gap: 22px
        }

        .unit-sw {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .8rem
        }

        .unit-sw .on {
            color: #fff;
            font-weight: 600
        }

        .unit-sw .off {
            color: rgba(255, 255, 255, .38);
            cursor: pointer
        }

        .unit-sw .sep {
            color: rgba(255, 255, 255, .25)
        }

        .menu-lbl {
            font-size: .78rem;
            letter-spacing: .24em;
            color: rgba(255, 255, 255, .7);
            cursor: pointer;
        }

        /* ══ MIDSECTION ══ */
        .mid {
            flex: 1;
            display: flex;
            align-items: flex-end;
            gap: 0;
            padding: 0 40px 14px;
            overflow: hidden;
        }

        /* — Kiri: Suhu utama — */
        .weather-main {
            flex: 0 0 270px;
            padding-right: 28px;
            padding-bottom: 10px;
        }

        .wi-big {
            font-size: 4.8rem;
            line-height: 1;
            animation: floaty 3.5s ease-in-out infinite;
            filter: drop-shadow(0 6px 16px rgba(0, 0, 0, .45));
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-11px)
            }
        }

        .w-datetime {
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .6);
            margin-top: 10px;
        }

        .w-temp {
            font-size: 5.8rem;
            font-weight: 300;
            line-height: 1;
            margin-top: 2px;
            letter-spacing: -.02em;
        }

        .w-feels {
            font-size: .95rem;
            color: rgba(255, 255, 255, .6);
            margin-top: 5px
        }

        .w-cond {
            font-size: .75rem;
            color: rgba(255, 255, 255, .38);
            margin-top: 3px
        }

        .w-sun {
            margin-top: 10px;
            font-size: .72rem;
            color: rgba(255, 255, 255, .38);
            line-height: 1.9;
        }

        /* divider vertikal */
        .vdiv {
            width: 1px;
            background: rgba(255, 255, 255, .12);
            align-self: stretch;
            margin-bottom: 10px;
        }

        /* — Tengah: Detail — */
        .w-detail {
            flex: 0 0 220px;
            padding: 0 26px 10px;
        }

        .w-detail h4 {
            font-size: .65rem;
            letter-spacing: .18em;
            color: rgba(255, 255, 255, .38);
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .drow {
            display: flex;
            justify-content: space-between;
            font-size: .78rem;
            color: rgba(255, 255, 255, .58);
            padding: 5px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
        }

        .drow .val {
            color: #f5c842;
            font-weight: 500
        }

        /* — Kanan: Per jam — */
        .w-hourly {
            flex: 1;
            padding: 0 0 10px 26px;
            overflow: hidden;
        }

        .hourly-periods {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            font-size: .6rem;
            letter-spacing: .1em;
            color: rgba(255, 255, 255, .3);
            text-transform: uppercase;
            margin-bottom: 6px;
            text-align: center;
        }

        .hcols {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 5px;
        }

        .hc {
            text-align: center;
            padding: 9px 3px 7px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .065);
            border: 1px solid rgba(255, 255, 255, .09);
            transition: background .2s, transform .2s;
            cursor: default;
        }

        .hc:hover {
            background: rgba(255, 255, 255, .14);
            transform: translateY(-5px)
        }

        .hc .hi {
            font-size: 1.45rem;
            line-height: 1.3
        }

        .hc .ht {
            font-size: .88rem;
            font-weight: 600;
            margin-top: 4px
        }

        .hc .hh {
            font-size: .62rem;
            color: rgba(255, 255, 255, .38);
            margin-top: 3px
        }

        /* ══ BOTTOM ══ */
        .btm {
            flex-shrink: 0;
            background: rgba(0, 0, 0, .44);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-top: 1px solid rgba(255, 255, 255, .1);
            padding: 12px 40px 14px;
        }

        .btm-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 9px;
        }

        .tab-today {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            border-bottom: 2px solid #f5c842;
            padding-bottom: 3px;
        }

        .show10 {
            font-size: .68rem;
            letter-spacing: .1em;
            color: rgba(255, 255, 255, .35);
            text-transform: uppercase;
            cursor: pointer;
            transition: color .2s;
        }

        .show10:hover {
            color: rgba(255, 255, 255, .8)
        }

        .weekly {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .wd {
            text-align: center;
            padding: 7px 4px 8px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .08);
            transition: background .2s;
            cursor: default;
        }

        .wd:hover {
            background: rgba(255, 255, 255, .11)
        }

        .wd .wn {
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .1em;
            color: rgba(255, 255, 255, .45);
            text-transform: uppercase
        }

        .wd .wdt {
            font-size: .58rem;
            color: rgba(255, 255, 255, .28);
            margin-bottom: 3px
        }

        .wd .wmm {
            font-size: .68rem;
            color: rgba(255, 255, 255, .45)
        }

        .wd .wmm b {
            color: #fff
        }

        .wd .wi {
            font-size: 1.55rem;
            line-height: 1.5
        }

        .wd .wc {
            font-size: .58rem;
            color: rgba(255, 255, 255, .3);
            line-height: 1.35;
            margin-top: 2px
        }

        /* ── Stagger ── */
        .fi {
            opacity: 0;
            transform: translateY(16px);
            animation: fadeIn .55s forwards
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .fi.d1 {
            animation-delay: .05s
        }

        .fi.d2 {
            animation-delay: .12s
        }

        .fi.d3 {
            animation-delay: .20s
        }

        .fi.d4 {
            animation-delay: .28s
        }

        .fi.d5 {
            animation-delay: .36s
        }

        .fi.d6 {
            animation-delay: .43s
        }

        .fi.d7 {
            animation-delay: .50s
        }

        .fi.d8 {
            animation-delay: .57s
        }

        .fi.d9 {
            animation-delay: .64s
        }

        /* ── Responsive ── */
        @media(max-width:960px) {
            .mid {
                flex-wrap: wrap;
                overflow-y: auto;
                padding: 0 16px 10px;
                gap: 8px
            }

            .weather-main {
                flex: 0 0 100%;
                padding-right: 0;
                padding-bottom: 8px
            }

            .vdiv {
                display: none
            }

            .w-detail {
                flex: 0 0 100%;
                padding: 8px 0
            }

            .w-hourly {
                flex: 0 0 100%;
                padding: 8px 0
            }

            .topbar,
            .btm {
                padding-left: 16px;
                padding-right: 16px
            }

            .w-temp {
                font-size: 4rem
            }

            .weekly {
                grid-template-columns: repeat(4, 1fr)
            }
        }

        @media(max-width:540px) {

            .hcols,
            .hourly-periods {
                grid-template-columns: repeat(4, 1fr)
            }

            .weekly {
                grid-template-columns: repeat(3, 1fr)
            }
        }
    </style>
</head>

<body>

    <div class="bg">
        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1800&q=80"
            alt="panorama gunung sunset" />
    </div>

    <div class="app">

        <!-- ══ TOPBAR ══ -->
        <header class="topbar fi d1">
            <div class="logo">Synoptic</div>
            <div class="loc-pill">
                <span>📍</span>
                <span>Cuaca di <b>
                        <?= htmlspecialchars($kota) ?>
                    </b> /
                    <?= htmlspecialchars($wilayah) ?>
                </span>
                <span style="opacity:.5">▾</span>
            </div>
            <div class="right-nav">
                <div class="unit-sw">
                    <span class="on">°C</span>
                    <span class="sep"> | </span>
                    <span class="off" id="togF">°F</span>
                </div>
                <div class="menu-lbl">M E N U</div>
            </div>
        </header>

        <!-- ══ MID ══ -->
        <div class="mid">

            <!-- Kiri: suhu utama -->
            <div class="weather-main fi d2">
                <div class="wi-big">⛅</div>
                <p class="w-datetime">
                    <?= $hari_str ?>
                    <?= $tgl_str ?>
                    <?= $bln_str ?>
                    <?= $thn_str ?> &nbsp;
                    <?= $jam_str ?>
                </p>
                <p class="w-temp">+
                    <?= $suhu ?>°C
                </p>
                <p class="w-feels">Terasa seperti
                    <?= $terasa ?>°
                </p>
                <p class="w-cond">
                    <?= htmlspecialchars($kondisi) ?>
                </p>
                <div class="w-sun">
                    <div>Terbit:&nbsp;&nbsp;&nbsp;&nbsp;
                        <?= $terbit ?>
                    </div>
                    <div>Terbenam:
                        <?= $terbenam ?>
                    </div>
                </div>
            </div>

            <div class="vdiv fi d2"></div>

            <!-- Tengah: detail -->
            <div class="w-detail fi d3">
                <h4>Detail lebih lanjut:</h4>
                <?php foreach ($detail as $k => $v): ?>
                    <div class="drow">
                        <span>
                            <?= htmlspecialchars($k) ?>:
                        </span>
                        <span class="val">
                            <?= htmlspecialchars($v) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="vdiv fi d3"></div>

            <!-- Kanan: per jam -->
            <div class="w-hourly fi d4">
                <div class="hourly-periods">
                    <?php
                    $prev = '';
                    foreach ($per_jam as $j) {
                        echo '<span>' . ($j['periode'] !== $prev ? $j['periode'] : '') . '</span>';
                        $prev = $j['periode'];
                    }
                    ?>
                </div>
                <div class="hcols">
                    <?php foreach ($per_jam as $j): ?>
                        <div class="hc">
                            <div class="hi">
                                <?= $j['ikon'] ?>
                            </div>
                            <div class="ht">+
                                <?= $j['suhu'] ?>°
                            </div>
                            <div class="hh">
                                <?= $j['label'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div><!-- /mid -->

        <!-- ══ BOTTOM ══ -->
        <div class="btm fi d5">
            <div class="btm-head">
                <span class="tab-today">HARI INI</span>
                <span class="show10">TAMPILKAN 10 HARI ›</span>
            </div>
            <div class="weekly">
                <?php foreach ($mingguan as $i => $m): ?>
                    <div class="wd fi d<?= min($i + 6, 9) ?>">
                        <p class="wn">
                            <?= $m['hari'] ?>
                        </p>
                        <p class="wdt">
                            <?= $m['tgl'] ?>
                        </p>
                        <p class="wmm">min. <b>+
                                <?= $m['min'] ?>°
                            </b></p>
                        <p class="wmm">max. <b>+
                                <?= $m['max'] ?>°
                            </b></p>
                        <p class="wi">
                            <?= $m['ikon'] ?>
                        </p>
                        <p class="wc">
                            <?= htmlspecialchars($m['label']) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div><!-- /app -->

    <script>
        /* Toggle unit °C / °F */
        let isCelsius = true;
        const temps = <?= json_encode(array_merge(
            [['sel' => '.w-temp', 'val' => $suhu], ['sel' => '.w-feels', 'val' => $terasa]],
            array_map(fn($j, $i) => ['sel' => ".ht:nth-child(" . ($i + 1) . ")global", 'raw' => $j['suhu']], $per_jam, array_keys($per_jam))
        )) ?>;

        document.getElementById('togF').addEventListener('click', function () {
            isCelsius = !isCelsius;
            document.querySelector('.unit-sw .on').classList.remove('on');
            document.querySelector('.unit-sw .off').classList.remove('off');
            this.classList.toggle('on', !isCelsius);
            document.querySelector('.unit-sw span:first-child').classList.toggle('on', isCelsius);
            // update tampilan suhu utama
            const c = <?= $suhu ?>;
            const f = Math.round(c * 9 / 5 + 32);
            document.querySelector('.w-temp').textContent = (isCelsius ? `+${c}°C` : `+${f}°F`);
            const cf = <?= $terasa ?>;
            const ff = Math.round(cf * 9 / 5 + 32);
            document.querySelector('.w-feels').textContent = `Terasa seperti ${isCelsius ? cf : ff}°`;
            // update per jam
            document.querySelectorAll('.hc .ht').forEach((el, i) => {
                const raw = [<?= implode(',', array_column($per_jam, 'suhu')) ?>][i];
                el.textContent = '+' + (isCelsius ? raw : Math.round(raw * 9 / 5 + 32)) + '°';
            });
        });
    </script>
</body>

</html>