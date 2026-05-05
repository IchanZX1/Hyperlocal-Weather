<div class="btm">
    <div class="weekly">
        <?php foreach ($mingguan as $m): ?>
            <div class="wd">
                <div style="font-size: 0.6rem; font-weight: 700; color: #f5c842;"><?= $m['hari'] ?></div>
                <div style="font-size: 0.6rem; opacity: 0.5;"><?= $m['tgl'] ?></div>
                <div style="font-size: 1.5rem; margin: 8px 0;"><?= $m['ikon'] ?></div>
                <div style="font-weight: 700;"><?= $m['max'] ?>°</div>
                <div style="font-size: 0.6rem; opacity: 0.5;"><?= $m['min'] ?>°</div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
