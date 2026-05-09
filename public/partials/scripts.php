<script>
    function toggleModal() {
        const modal = document.getElementById('locModal');
        modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
        if (modal.style.display === 'flex' && document.getElementById('provinsi').options.length <= 1) {
            fetchProvinces();
        }
    }

    // Auto Geolocation Logic
    window.addEventListener('load', () => {
        const urlParams = new URLSearchParams(window.location.search);
        // Jika tidak ada parameter lokasi (kab/lat), minta izin lokasi
        if (!urlParams.has('kab') && !urlParams.has('lat')) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    // Redirect dengan koordinat
                    window.location.href = `?lat=${lat}&lon=${lon}`;
                }, (error) => {
                    console.log("Geolocation ditolak atau error:", error);
                    // Jika ditolak, biarkan menggunakan default Jember (sudah diatur di PHP)
                });
            }
        }
    });

    const baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    async function fetchProvinces() {
        try {
            const response = await fetch(`${baseUrl}/provinces.json`);
            const data = await response.json();
            const select = document.getElementById('provinsi');
            data.forEach(p => {
                let opt = document.createElement('option');
                opt.value = p.id;
                opt.text = p.name;
                select.add(opt);
            });
        } catch (e) { console.error("Gagal load provinsi"); }
    }

    document.getElementById('provinsi').addEventListener('change', async function() {
        const id = this.value;
        const name = this.options[this.selectedIndex].text;
        document.getElementById('prov_name').value = name;
        
        const kab = document.getElementById('kabupaten');
        kab.innerHTML = '<option value="">Pilih Kabupaten</option>';
        kab.disabled = true;
        document.getElementById('kecamatan').innerHTML = '<option value="">Pilih Kecamatan</option>';
        document.getElementById('kecamatan').disabled = true;
        document.getElementById('desa').innerHTML = '<option value="">Pilih Desa</option>';
        document.getElementById('desa').disabled = true;

        if (id) {
            const response = await fetch(`${baseUrl}/regencies/${id}.json`);
            const data = await response.json();
            data.forEach(r => {
                let opt = document.createElement('option');
                opt.value = r.id;
                opt.text = r.name;
                kab.add(opt);
            });
            kab.disabled = false;
        }
    });

    document.getElementById('kabupaten').addEventListener('change', async function() {
        const id = this.value;
        const name = this.options[this.selectedIndex].text;
        document.getElementById('kab_name').value = name;

        const kec = document.getElementById('kecamatan');
        kec.innerHTML = '<option value="">Pilih Kecamatan</option>';
        kec.disabled = true;
        document.getElementById('desa').innerHTML = '<option value="">Pilih Desa</option>';
        document.getElementById('desa').disabled = true;

        if (id) {
            const response = await fetch(`${baseUrl}/districts/${id}.json`);
            const data = await response.json();
            data.forEach(d => {
                let opt = document.createElement('option');
                opt.value = d.id;
                opt.text = d.name;
                kec.add(opt);
            });
            kec.disabled = false;
        }
    });

    document.getElementById('kecamatan').addEventListener('change', async function() {
        const id = this.value;
        const name = this.options[this.selectedIndex].text;
        document.getElementById('kec_name').value = name;

        const desa = document.getElementById('desa');
        desa.innerHTML = '<option value="">Pilih Desa</option>';
        desa.disabled = true;

        if (id) {
            const response = await fetch(`${baseUrl}/villages/${id}.json`);
            const data = await response.json();
            data.forEach(v => {
                let opt = document.createElement('option');
                opt.value = v.id;
                opt.text = v.name;
                desa.add(opt);
            });
            desa.disabled = false;
        }
    });

    document.getElementById('desa').addEventListener('change', function() {
        document.getElementById('desa_name').value = this.options[this.selectedIndex].text;
    });

    document.getElementById('locForm').addEventListener('submit', function(e) {
        if (!document.getElementById('desa_name').value) {
            e.preventDefault();
            alert('Silakan pilih lokasi sampai tingkat Desa.');
        }
    });
</script>


