<div class="w-hourly">
    <div class="hcols">
        <?php foreach ($per_jam as $h): ?>
            <div class="hc">
                <div style="font-size: 0.7rem; opacity: 0.5; margin-bottom: 5px;"><?= $h['label'] ?></div>
                <div style="font-size: 1.5rem;"><?= $h['ikon'] ?></div>
                <div style="font-weight: 700; margin-top: 5px;"><?= $h['suhu'] ?>°</div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
