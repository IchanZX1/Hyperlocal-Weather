<div id="locModal">
    <div class="modal-content">
        <h2>Set Lokasi</h2>
        <form action="" method="GET" id="locForm">
            <div class="form-group">
                <label>Provinsi</label>
                <select id="provinsi" required>
                    <option value="">Pilih Provinsi</option>
                </select>
            </div>
            <div class="form-group">
                <label>Kabupaten / Kota</label>
                <select id="kabupaten" required disabled>
                    <option value="">Pilih Kabupaten</option>
                </select>
            </div>
            <div class="form-group">
                <label>Kecamatan</label>
                <select id="kecamatan" required disabled>
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Desa</label>
                <select id="desa" required disabled>
                    <option value="">Pilih Desa</option>
                </select>
            </div>

            <input type="hidden" id="prov_name" name="prov_name">
            <input type="hidden" id="kab_name" name="kab">
            <input type="hidden" id="kec_name" name="kec">
            <input type="hidden" id="desa_name" name="desa">

            <button type="submit" class="btn-set">Terapkan Lokasi</button>
            <button type="button" onclick="toggleModal()" class="btn-cancel">Batal</button>
        </form>
    </div>
</div>



