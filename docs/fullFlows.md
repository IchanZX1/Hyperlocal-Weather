# 🌦️ Weather System Architecture
## EMSIFA + OpenStreetMap + Ewalabs

---

# 📌 Tujuan Sistem

Membuat sistem cuaca berbasis wilayah Indonesia dengan alur:

```txt
Wilayah Indonesia
→ Konversi ke koordinat (lat/lon)
→ Ambil data cuaca
```

Sumber data:

| Service | Fungsi |
|---|---|
| EMSIFA | Data wilayah Indonesia |
| Nominatim OpenStreetMap | Geocoding wilayah → lat/lon |
| Weather Ewalabs | Data cuaca berdasarkan koordinat |

---

# 🧠 Core Concept

EMSIFA tidak menyediakan:
- latitude
- longitude

Maka diperlukan:
- geocoding service

OpenStreetMap Nominatim digunakan untuk:
- mencari koordinat wilayah berdasarkan nama lokasi.

Setelah mendapatkan:
- `lat`
- `lon`

maka Weather Ewalabs dapat digunakan untuk mengambil cuaca.

---

# 🏗️ System Architecture

```txt
┌─────────────────────┐
│     User Select     │
│  Province/District  │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│     EMSIFA API      │
│  Data Wilayah Indo  │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  Generate Address   │
│ desa+kecamatan+kab  │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ OpenStreetMap API   │
│     Geocoding       │
│   Get lat & lon     │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  Save Coordinates   │
│ Cache / Database    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Weather Ewalabs API │
│   Forecast Weather  │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│    Display Result   │
│ Weather Information │
└─────────────────────┘
```

---

# 🔄 Full Flow Logic

## STEP 1 — User memilih wilayah

Contoh:

```txt
Provinsi  : Jawa Timur
Kabupaten : Bondowoso
Kecamatan : Klabang
Desa      : Karanganyar
```

---

# STEP 2 — Ambil data wilayah dari EMSIFA

## Example API

```txt
https://www.emsifa.com/api-wilayah-indonesia/api/villages/3511150.json
```

## Response

```json
[
  {
    "id": "3511150005",
    "district_id": "3511150",
    "name": "KARANGANYAR"
  }
]
```

---

# STEP 3 — Generate alamat lengkap

Gabungkan wilayah menjadi query lokasi.

## Example

```txt
KARANGANYAR, KLABANG, BONDOWOSO, JAWA TIMUR
```

Tujuan:
- agar geocoding lebih akurat.

---

# STEP 4 — Geocoding menggunakan OpenStreetMap

## Request

```txt
https://nominatim.openstreetmap.org/search?format=json&q=KARANGANYAR,KLABANG,BONDOWOSO,JAWA%20TIMUR
```

## Response

```json
[
  {
    "lat": "-7.9123",
    "lon": "113.8211",
    "display_name": "Karanganyar, Klabang, Bondowoso..."
  }
]
```

---

# STEP 5 — Ambil latitude & longitude

## Example

```js
const lat = data[0].lat;
const lon = data[0].lon;
```

---

# STEP 6 — Request cuaca ke Ewalabs

## Request

```txt
https://weather.ewalabs.com/api/v1?lat=-7.9123&lon=113.8211
```

## Response

```json
{
  "current": {
    "temperature": 27,
    "weather": "Cloudy"
  }
}
```

---

# STEP 7 — Tampilkan data cuaca

Contoh:

```txt
Cuaca: Berawan
Suhu : 27°C
Kelembaban : 81%
```

---

# 💾 Recommended Database Structure

## wilayah_cache.json

```json
{
  "3511150005": {
    "desa": "KARANGANYAR",
    "kecamatan": "KLABANG",
    "kabupaten": "BONDOWOSO",
    "provinsi": "JAWA TIMUR",
    "lat": -7.9123,
    "lon": 113.8211
  }
}
```

---

# ⚡ Kenapa Cache Penting?

Tanpa cache:

```txt
Setiap request cuaca
→ request ke OpenStreetMap
→ lambat
→ rate limit
```

Dengan cache:

```txt
Cari koordinat sekali saja
→ simpan
→ gunakan selamanya
```

---

# 🧠 Recommended Flow Production

## First Request

```txt
EMSIFA
→ OpenStreetMap
→ Simpan lat/lon
→ Ewalabs
```

## Next Request

```txt
Database Cache
→ Ewalabs
```

Tanpa geocoding ulang.

---

# 📦 Full Coding Logic

## Main Function

```js
async function getWeather(village){

    // 1. cek cache
    const cache = await getCache(village.id);

    let lat;
    let lon;

    // 2. jika cache ada
    if(cache){

        lat = cache.lat;
        lon = cache.lon;

    }else{

        // 3. generate alamat
        const query = `
            ${village.name},
            ${village.district},
            ${village.regency},
            ${village.province}
        `;

        // 4. geocoding
        const geo = await fetch(
            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`
        ).then(r => r.json());

        lat = geo[0].lat;
        lon = geo[0].lon;

        // 5. simpan cache
        saveCache(village.id, lat, lon);

    }

    // 6. request weather
    const weather = await fetch(
        `https://weather.ewalabs.com/api/v1?lat=${lat}&lon=${lon}`
    ).then(r => r.json());

    return weather;
}
```

---

# 🔥 Best Practice

## ✅ Gunakan cache

Agar:
- hemat request
- lebih cepat
- tidak kena rate limit

---

## ✅ Tambahkan normalize text

Kadang nama wilayah berbeda format.

Contoh:

```txt
KARANGANYAR
Karang Anyar
karanganyar
```

Gunakan:

```js
function normalize(text){
    return text
        .toLowerCase()
        .replace(/\s+/g,'')
        .trim();
}
```

---

# ⚠️ Possible Problems

| Problem | Solution |
|---|---|
| Nama wilayah tidak ditemukan | tambah kabupaten & provinsi |
| Geocoding salah lokasi | gunakan query lebih lengkap |
| Rate limit OSM | gunakan cache |
| Banyak request | simpan database koordinat |

---

# 🚀 Final Recommended Architecture

```txt
EMSIFA
   ↓
Wilayah Indonesia
   ↓
OpenStreetMap Geocoding
   ↓
Latitude & Longitude
   ↓
Cache Database
   ↓
Weather Ewalabs API
   ↓
Frontend Display
```

---

# ✅ Final Conclusion

## EMSIFA
Digunakan untuk:
- struktur wilayah Indonesia

## OpenStreetMap
Digunakan untuk:
- mengubah nama wilayah → koordinat

## Ewalabs
Digunakan untuk:
- data cuaca berdasarkan koordinat

---

# 🎯 Keuntungan Arsitektur Ini

✅ Tidak tergantung BMKG  
✅ Tidak perlu adm4  
✅ Universal  
✅ Fleksibel  
✅ Mudah dikembangkan  
✅ Bisa dipakai untuk:
- maps
- cuaca
- nearby search
- tracking
- GIS system

```