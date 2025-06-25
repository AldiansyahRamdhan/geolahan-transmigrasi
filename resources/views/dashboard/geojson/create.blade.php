@extends('dashboard.layouts.main')

@section('content')
    <style>
        #map {
            height: 500px;
            margin-top: 20px;
        }

        .leaflet-container {
            z-index: 0;
        }

        .leaflet-draw {
            z-index: 1000 !important;
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5>Tambah Data Lokasi Tanah Transmigrasi</h5>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tanah.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mt-3" for="pemilik">Pemilik Tanah</label>
                                <input type="text" class="form-control @error('pemilik') is-invalid @enderror"
                                    id="pemilik" name="pemilik" value="{{ old('pemilik') }}" required>
                                @error('pemilik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="nib">NIB</label>
                                <input type="text" class="form-control @error('nib') is-invalid @enderror" id="nib"
                                    name="nib" value="{{ old('nib') }}" required>
                                @error('nib')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="tahun">Tahun</label>
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                    id="tahun" name="tahun" value="{{ old('tahun') }}" required>
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="tipe_hak">Tipe Hak</label>
                                <input type="text" step="0.01"
                                    class="form-control @error('tipe_hak') is-invalid @enderror" id="tipe_hak"
                                    name="tipe_hak" value="{{ old('tipe_hak') }}" required>
                                @error('tipe_hak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="luas">Luas Tanah</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('luas') is-invalid @enderror" id="luas" name="luas"
                                    value="{{ old('luas') }}" required>
                                @error('luas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="kelurahan">kelurahan</label>
                                <input type="text" class="form-control @error('kelurahan') is-invalid @enderror"
                                    id="kelurahan" name="kelurahan" value="{{ old('kelurahan') }}" required>
                                @error('kelurahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="kecamatan">Kecamatan</label>
                                <input type="text" class="form-control @error('kecamatan') is-invalid @enderror"
                                    id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}" required>
                                @error('kecamatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="geojson">GeoJSON (Titik Lokasi)</label>
                                <textarea class="form-control @error('geojson') is-invalid @enderror" name="geojson" id="geojson" readonly required>{{ old('geojson') }}</textarea>
                                @error('geojson')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="col-md-6">


                            <div class="form-group">
                                <label class="mt-3" for="penggunaan">Penggunaan Tanah</label>
                                <select class="form-control @error('penggunaan') is-invalid @enderror" id="penggunaan"
                                    name="penggunaan" required>
                                    <option value="">Pilih...</option>
                                    @foreach ($rekomendasis->unique('penggunaan') as $r)
                                        <option value="{{ $r->penggunaan }}"
                                            {{ old('penggunaan') == $r->penggunaan ? 'selected' : '' }}>
                                            {{ $r->penggunaan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('penggunaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="mt-3" for="jenis_tanah">Jenis Tanah</label>
                                <select class="form-control @error('jenis_tanah') is-invalid @enderror" id="jenis_tanah"
                                    name="jenis_tanah" required>
                                    <option value="">Pilih...</option>
                                    @foreach ($rekomendasis->unique('jenis_tanah') as $r)
                                        <option value="{{ $r->jenis_tanah }}"
                                            {{ old('jenis_tanah') == $r->jenis_tanah ? 'selected' : '' }}>
                                            {{ $r->jenis_tanah }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_tanah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="kadar_air" class="mt-3">Kadar Air</label>
                                <select class="form-control @error('kadar_air') is-invalid @enderror" id="kadar_air"
                                    name="kadar_air" required>
                                    <option value="">Pilih...</option>
                                    @foreach ($rekomendasis->unique('kadar_air') as $r)
                                        <option value="{{ $r->kadar_air }}"
                                            {{ old('kadar_air') == $r->kadar_air ? 'selected' : '' }}>
                                            {{ $r->kadar_air }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kadar_air')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="lereng" class="mt-3">Lereng</label>
                                <select class="form-control @error('lereng') is-invalid @enderror" id="lereng"
                                    name="lereng" required>
                                    <option value="">Pilih...</option>
                                    @foreach ($rekomendasis->unique('lereng') as $r)
                                        <option value="{{ $r->lereng }}"
                                            {{ old('lereng') == $r->lereng ? 'selected' : '' }}>
                                            {{ $r->lereng }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('lereng')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="rekomendasi_tanaman" class="mt-3">Rekomendasi Tanaman</label>
                                <input type="text" class="form-control" id="rekomendasi_tanaman"
                                    name="rekomendasi_tanaman" readonly value="{{ old('rekomendasi_tanaman') }}">
                            </div>


                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan Data</button>
                </form>
            </div>
        </div>

        <!-- Peta -->
        <div id="map" style="height: 500px;"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
    <script src="https://unpkg.com/leaflet-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        const rekomendasiData = @json($rekomendasis);

        function updateRekomendasiTanaman() {
            const penggunaan = document.getElementById('penggunaan').value;
            const jenis_tanah = document.getElementById('jenis_tanah').value;
            const kadar_air = document.getElementById('kadar_air').value;
            const lereng = document.getElementById('lereng').value;

            // Cek apakah semua input sudah diisi
            if (!penggunaan || !jenis_tanah || !kadar_air || !lereng) {
                document.getElementById('rekomendasi_tanaman').value = '';
                return; // Kalau belum lengkap, kosongkan dan keluar
            }

            const hasil = rekomendasiData.find(item =>
                item.penggunaan === penggunaan &&
                item.jenis_tanah === jenis_tanah &&
                item.kadar_air === kadar_air &&
                item.lereng === lereng
            );

            document.getElementById('rekomendasi_tanaman').value = hasil ? hasil.rekomendasi_tanaman :
                'Rekomendasi tidak ditemukan';
        }

        document.getElementById('penggunaan').addEventListener('change', updateRekomendasiTanaman);
        document.getElementById('jenis_tanah').addEventListener('change', updateRekomendasiTanaman);
        document.getElementById('kadar_air').addEventListener('change', updateRekomendasiTanaman);
        document.getElementById('lereng').addEventListener('change', updateRekomendasiTanaman);
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === Batas wilayah dalam bentuk FeatureCollection ===
            const batasWilayahFC = {!! $batas->batas !!};

            // Ambil polygon pertama dari FeatureCollection
            const batasWilayah = batasWilayahFC.features[0];

            // === Polygon masking: merah di luar batas ===
            const outerRing = [
                [180, 90],
                [-180, 90],
                [-180, -90],
                [180, -90],
                [180, 90]
            ];

            const maskingLayer = {
                type: "Feature",
                geometry: {
                    type: "Polygon",
                    coordinates: [
                        outerRing,
                        ...batasWilayah.geometry.coordinates // lubang (hole)
                    ]
                },
                properties: {}
            };

            // Base layers
            var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            });

            var satelliteLayer = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: 'Tiles © Esri'
                }
            );

            // Inisialisasi peta
            var map = L.map('map', {
                center: [-7.2626, 106.9179],
                zoom: 15,
                layers: [osmLayer]
            });

            var baseMaps = {
                "OpenStreetMap": osmLayer,
                "Citra Satelit": satelliteLayer
            };
            L.control.layers(baseMaps).addTo(map);

            // Tampilkan area luar batas sebagai merah transparan
            L.geoJSON(maskingLayer, {
                style: {
                    color: 'red',
                    fillColor: 'red',
                    fillOpacity: 0.3,
                    weight: 0
                }
            }).addTo(map);

            // Tampilkan garis batas wilayah
            const batasLayer = L.geoJSON(batasWilayah, {
                style: {
                    color: 'red',
                    fillOpacity: 0,
                    weight: 2
                }
            }).addTo(map);
            map.fitBounds(batasLayer.getBounds());

            // ====== Layer data tanah dari API ======
            fetch('/api/tanah-geojson')
                .then(response => response.json())
                .then(data => {
                    if (data && data.features && data.features.length > 0) {
                        const geojsonLayer = L.geoJSON(data, {
                            onEachFeature: function(feature, layer) {
                                const props = feature.properties;
                                layer.bindPopup(`
                                <strong>${props.nama}</strong><br>
                                NIK: ${props.nik}<br>
                                Desa: ${props.desa}<br>
                                Rekomendasi: ${props.rekomendasi_tanaman}
                            `);
                            },
                            style: {
                                color: '#3388ff',
                                weight: 2
                            }
                        }).addTo(map);
                    }
                })
                .catch(error => {
                    console.error('Gagal memuat GeoJSON:', error);
                });

            // ====== Drawing ======
            var drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            var drawControl = new L.Control.Draw({
                draw: {
                    polygon: true,
                    rectangle: true,
                    polyline: false,
                    circle: false,
                    marker: true,
                    circlemarker: false
                },
                edit: {
                    featureGroup: drawnItems
                }
            });
            map.addControl(drawControl);

            // Validasi gambar berada DI DALAM batas
            map.on('draw:created', function(e) {
                var layer = e.layer;
                const geojson = layer.toGeoJSON();

                const isInside = turf.booleanWithin(geojson, batasWilayah);

                if (!isInside) {
                    alert('Gambar HARUS berada DI DALAM area putih (tidak boleh di area merah).');
                    return;
                }

                drawnItems.clearLayers();
                drawnItems.addLayer(layer);

                const hasil = drawnItems.toGeoJSON();
                const output = document.getElementById('geojson');
                if (output) {
                    output.value = JSON.stringify(hasil, null, 2);
                }
            });

            // ====== Geocoder (pencarian lokasi) ======
            if (typeof L.Control.Geocoder !== 'undefined') {
                L.Control.geocoder({
                        defaultMarkGeocode: false
                    })
                    .on('markgeocode', function(e) {
                        var bbox = e.geocode.bbox;
                        var poly = L.polygon([
                            bbox.getSouthEast(),
                            bbox.getNorthEast(),
                            bbox.getNorthWest(),
                            bbox.getSouthWest()
                        ]).addTo(map);
                        map.fitBounds(poly.getBounds());
                    })
                    .addTo(map);
            } else {
                console.warn("Plugin Geocoder Leaflet tidak ditemukan.");
            }
        });
    </script>



@endsection
