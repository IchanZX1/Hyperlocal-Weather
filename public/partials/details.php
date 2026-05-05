<div class="w-detail">
    <h4>Kondisi Saat Ini</h4>
    <?php foreach ($detail as $label => $val): ?>
        <div class="drow">
            <span><?= $label ?></span>
            <span class="val"><?= $val ?></span>
        </div>
    <?php endforeach; ?>
</div>
