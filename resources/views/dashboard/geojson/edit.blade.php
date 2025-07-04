@extends('dashboard.layouts.main')

@section('content')
    <style>
        #map {
            height: 500px;
            margin-top: 20px;
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5>Edit Data Tanah</h5>
                <form action="{{ route('tanah.update', $tanah->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mt-3" for="pemilik">Pemilik Tanah</label>
                                <input type="text" class="form-control @error('pemilik') is-invalid @enderror"
                                    id="pemilik" name="pemilik" value="{{ old('pemilik', $tanah->pemilik ?? '') }}"
                                    required>
                                @error('pemilik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="nib">NIB</label>
                                <input type="text" class="form-control @error('nib') is-invalid @enderror" id="nib"
                                    name="nib" value="{{ old('nib', $tanah->nib ?? '') }}" required>
                                @error('nib')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="tahun">Tahun</label>
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                    id="tahun" name="tahun" value="{{ old('tahun', $tanah->tahun ?? '') }}" required>
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="tipe_hak">Tipe Hak</label>
                                <input type="text" step="0.01"
                                    class="form-control @error('tipe_hak') is-invalid @enderror" id="tipe_hak"
                                    name="tipe_hak" value="{{ old('tipe_hak', $tanah->tipe_hak ?? '') }}" required>
                                @error('tipe_hak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="luas">Luas Tanah</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('luas') is-invalid @enderror" id="luas" name="luas"
                                    value="{{ old('luas', $tanah->luas ?? '') }}" required>
                                @error('luas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="kelurahan">kelurahan</label>
                                <input type="text" class="form-control @error('kelurahan') is-invalid @enderror"
                                    id="kelurahan" name="kelurahan" value="{{ old('kelurahan', $tanah->kelurahan ?? '') }}"
                                    required>
                                @error('kelurahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mt-3" for="kecamatan">Kecamatan</label>
                                <input type="text" class="form-control @error('kecamatan') is-invalid @enderror"
                                    id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $tanah->kecamatan ?? '') }}"
                                    required>
                                @error('kecamatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>

                        <div class="col-md-6">

                            {{-- PENGGUNAAN TANAH --}}
                            <div class="form-group">
                                <label class="mt-3" for="penggunaan">Penggunaan Tanah</label>
                                <select class="form-control @error('penggunaan') is-invalid @enderror" id="penggunaan"
                                    name="penggunaan" required>
                                    <option value="">Pilih...</option>
                                    @foreach ($rekomendasis->unique('penggunaan') as $r)
                                        <option value="{{ $r->penggunaan }}"
                                            {{ old('penggunaan', $tanah->penggunaan) == $r->penggunaan ? 'selected' : '' }}>
                                            {{ $r->penggunaan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('penggunaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- JENIS TANAH --}}
                            <div class="form-group">
                                <label class="mt-3" for="jenis_tanah">Jenis Tanah</label>
                                <select class="form-control @error('jenis_tanah') is-invalid @enderror" id="jenis_tanah"
                                    name="jenis_tanah" required>
                                    <option value="">Pilih...</option>
                                    @foreach ($rekomendasis->unique('jenis_tanah') as $r)
                                        <option value="{{ $r->jenis_tanah }}"
                                            {{ old('jenis_tanah', $tanah->jenis_tanah) == $r->jenis_tanah ? 'selected' : '' }}>
                                            {{ $r->jenis_tanah }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_tanah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- KADAR AIR --}}
                            <div class="form-group">
                                <label for="kadar_air">Kadar Air</label>
                                <select class="form-control @error('kadar_air') is-invalid @enderror" id="kadar_air"
                                    name="kadar_air" required>
                                    <option value="">Pilih...</option>
                                    @foreach ($rekomendasis->unique('kadar_air') as $r)
                                        <option value="{{ $r->kadar_air }}"
                                            {{ old('kadar_air', $tanah->kadar_air) == $r->kadar_air ? 'selected' : '' }}>
                                            {{ $r->kadar_air }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kadar_air')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- LERENG --}}
                            <div class="form-group">
                                <label for="lereng">Lereng</label>
                                <select class="form-control @error('lereng') is-invalid @enderror" id="lereng"
                                    name="lereng" required>
                                    <option value="">Pilih...</option>
                                    @foreach ($rekomendasis->unique('lereng') as $r)
                                        <option value="{{ $r->lereng }}"
                                            {{ old('lereng', $tanah->lereng) == $r->lereng ? 'selected' : '' }}>
                                            {{ $r->lereng }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('lereng')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="rekomendasi_tanaman">Rekomendasi Tanaman</label>
                                <input type="text"
                                    class="form-control @error('rekomendasi_tanaman') is-invalid @enderror"
                                    id="rekomendasi_tanaman" name="rekomendasi_tanaman"
                                    value="{{ old('rekomendasi_tanaman', $tanah->rekomendasi_tanaman ?? '') }}" readonly>
                                @error('rekomendasi_tanaman')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="geojson">GeoJSON (Titik Lokasi)</label>
                                <textarea class="form-control @error('geojson') is-invalid @enderror" name="geojson" id="geojson" readonly
                                    required>{{ old('geojson', $tanah->geojson ?? '') }}</textarea>
                                @error('geojson')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>



                    <button type="submit" class="btn btn-success">Update Data</button>
                </form>
            </div>
        </div>

        <div id="map"></div>
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

            const hasil = rekomendasiData.find(item =>
                item.penggunaan === penggunaan &&
                item.jenis_tanah === jenis_tanah &&
                item.kadar_air === kadar_air &&
                item.lereng === lereng
            );

            document.getElementById('rekomendasi_tanaman').value =
                hasil ? hasil.rekomendasi_tanaman : 'Tidak ditemukan rekomendasi';
        }

        ['penggunaan', 'jenis_tanah', 'kadar_air', 'lereng'].forEach(id => {
            document.getElementById(id).addEventListener('change', updateRekomendasiTanaman);
        });

        // Panggil saat halaman dibuka jika semua data sudah terisi
        document.addEventListener('DOMContentLoaded', updateRekomendasiTanaman);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const batasWilayahFC = {!! $batas->batas !!}; // FeatureCollection
            const batasWilayah = batasWilayahFC.features[0]; // Ambil poligon pertama

            // === Buat masking luar merah ===
            const outerRing = [
                [180, 90],
                [-180, 90],
                [-180, -90],
                [180, -90],
                [180, 90]
            ];

            const maskingLayer = {
                type: "Feature",
                properties: {},
                geometry: {
                    type: "Polygon",
                    coordinates: [
                        outerRing,
                        ...batasWilayah.geometry.coordinates
                    ]
                }
            };

            // === Peta dasar
            var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            });

            var satellite = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: '© Esri'
                });

            var map = L.map('map', {
                center: [-7.2626, 106.9179],
                zoom: 15,
                layers: [osm]
            });

            var baseLayers = {
                "Peta Biasa": osm,
                "Citra Satelit": satellite
            };

            L.control.layers(baseLayers).addTo(map);

            // === Tambahkan masking merah
            L.geoJSON(maskingLayer, {
                style: {
                    color: 'red',
                    fillColor: 'red',
                    fillOpacity: 0.3,
                    weight: 0
                }
            }).addTo(map);

            // === Tampilkan batas wilayah (outline)
            const batasLayer = L.geoJSON(batasWilayah, {
                style: {
                    color: 'red',
                    fillOpacity: 0,
                    weight: 2
                }
            }).addTo(map);

            map.fitBounds(batasLayer.getBounds());

            // === Inisialisasi Layer Gambar
            var drawnItems = new L.FeatureGroup().addTo(map);

            @if ($tanah->geojson)
                var existingGeoJson = {!! $tanah->geojson !!};
                L.geoJSON(existingGeoJson).eachLayer(function(layer) {
                    drawnItems.addLayer(layer);
                    map.fitBounds(layer.getBounds());
                });
            @endif

            // === Control menggambar
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

            // === Validasi gambar agar di dalam batas
            map.on('draw:created', function(e) {
                drawnItems.clearLayers();

                var layer = e.layer;
                var geojson = layer.toGeoJSON();
                const isInside = turf.booleanWithin(geojson, batasWilayah);

                if (!isInside) {
                    alert('Gambar harus berada di dalam area putih (bukan area merah).');
                    return;
                }

                drawnItems.addLayer(layer);
                document.getElementById('geojson').value = JSON.stringify(drawnItems.toGeoJSON(), null, 2);
            });

            // === Update saat diedit
            map.on('draw:edited', function() {
                var geojson = drawnItems.toGeoJSON();
                document.getElementById('geojson').value = JSON.stringify(geojson, null, 2);
            });

            // === Geocoder
            if (typeof L.Control.Geocoder !== 'undefined') {
                L.Control.geocoder({
                        defaultMarkGeocode: false
                    })
                    .on('markgeocode', function(e) {
                        var bbox = e.geocode.bbox;
                        map.fitBounds(bbox);
                    }).addTo(map);
            }
        });
    </script>

@endsection
