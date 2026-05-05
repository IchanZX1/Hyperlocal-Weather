<div id="locModal">
    <div class="modal-content">
        <h2>Set Lokasi</h2>
        <form action="" method="GET">
            <div class="form-group">
                <label>Kabupaten</label>
                <input type="text" name="kab" value="<?= htmlspecialchars($kab) ?>" required>
            </div>
            <div class="form-group">
                <label>Kecamatan</label>
                <input type="text" name="kec" value="<?= htmlspecialchars($kec) ?>" required>
            </div>
            <div class="form-group">
                <label>Desa</label>
                <input type="text" name="desa" value="<?= htmlspecialchars($desa) ?>" required>
            </div>
            <button type="submit" class="btn-set">Terapkan Lokasi</button>
            <button type="button" onclick="toggleModal()"
                style="width: 100%; background: transparent; border: none; color: #888; margin-top: 10px; cursor: pointer;">Batal</button>
        </form>
    </div>
</div>
