@extends('dashboard.layouts.main')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
    <div class="container py-4">


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="card-title">
                        <h5>Daftar Tanah Transmigrasi</h5>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('tanah.create') }}" class="btn btn-primary">+ Tambah Data</a>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-warning ms-3" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Batas Wilayah
                        </button>
                    </div>


                </div>



                <!-- Modal -->
                <form action="{{ route('batas.store') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-fullscreen">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Pilih Titik Lokasi</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <div class="row g-0">
                                        <div class="col-md-4 p-3">
                                            <div class="form-group">
                                                <label for="geojson">GeoJSON (Titik Lokasi)</label>
                                                <textarea class="form-control @error('batas') is-invalid @enderror" name="batas" id="geojson" rows="12"
                                                    readonly required>{{ old('batas') }}</textarea>
                                                @error('batas')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div id="map" style="height: 100vh; width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan Lokasi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


                <!-- Leaflet Scripts -->
                <script>
                    let map, drawnItems;

                    document.addEventListener('DOMContentLoaded', function() {
                        const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap'
                        });

                        const satellite = L.tileLayer(
                            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                                attribution: '© Esri'
                            });

                        map = L.map('map', {
                            center: [-7.2626, 106.9179],
                            zoom: 15,
                            layers: [osm]
                        });

                        const baseLayers = {
                            "Peta Biasa": osm,
                            "Citra Satelit": satellite
                        };

                        L.control.layers(baseLayers).addTo(map);

                        drawnItems = new L.FeatureGroup().addTo(map);

                        const drawControl = new L.Control.Draw({
                            draw: {
                                polygon: true,
                                polyline: false,
                                rectangle: true,
                                circle: false,
                                marker: true,
                                circlemarker: false
                            },
                            edit: {
                                featureGroup: drawnItems
                            }
                        });

                        map.addControl(drawControl);

                        // Add existing data from backend
                        @if (!empty($batas->batas))
                            const existingGeoJson = {!! $batas->batas !!};
                            const geoLayer = L.geoJSON(existingGeoJson);
                            geoLayer.eachLayer(layer => {
                                drawnItems.addLayer(layer);
                            });
                            const bounds = geoLayer.getBounds();
                            if (bounds.isValid()) {
                                map.fitBounds(bounds);
                            }
                            document.getElementById('geojson').value = JSON.stringify(existingGeoJson, null, 2);
                        @endif

                        // When user draws new shape
                        map.on('draw:created', function(e) {
                            drawnItems.clearLayers();
                            drawnItems.addLayer(e.layer);
                            updateGeoJSON();
                            map.fitBounds(e.layer.getBounds());
                        });

                        map.on('draw:edited', function() {
                            updateGeoJSON();
                            const bounds = drawnItems.getBounds();
                            if (bounds.isValid()) {
                                map.fitBounds(bounds);
                            }
                        });

                        function updateGeoJSON() {
                            const geojson = drawnItems.toGeoJSON();
                            document.getElementById('geojson').value = JSON.stringify(geojson, null, 2);
                        }

                        // Invalidate map size when modal is shown
                        const modal = document.getElementById('exampleModal');
                        modal.addEventListener('shown.bs.modal', function() {
                            setTimeout(() => {
                                map.invalidateSize();

                                // Optional safety zoom if layers already drawn
                                const bounds = drawnItems.getBounds();
                                if (bounds.isValid()) {
                                    map.fitBounds(bounds);
                                }
                            }, 300);
                        });

                        // Optional geocoder support
                        if (typeof L.Control.Geocoder !== 'undefined') {
                            L.Control.geocoder({
                                defaultMarkGeocode: false
                            }).on('markgeocode', function(e) {
                                const bbox = e.geocode.bbox;
                                map.fitBounds(bbox);
                            }).addTo(map);
                        }
                    });
                </script>


                <div class="table-responsive">
                    <table class="table datatable align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Pemilik&nbsp;Tanah</th>
                                <th>NIB</th>
                                <th>Tahun</th>
                                <th>Kelurahan</th>
                                <th>Kecamatan</th>
                                <th>Luas&nbsp;(m²)</th>
                                <th>Penggunaan</th>
                                <th>Jenis&nbsp;Tanah</th>
                                <th>Kadar&nbsp;Air</th>
                                <th>Lereng</th>
                                <th>Rekomendasi&nbsp;Tanaman</th>

                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tanahs as $index => $tanah)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tanah->pemilik ?? '-' }}</td>
                                    <td>{{ $tanah->nib }}</td>

                                    <td>{{ $tanah->tahun }}</td>

                                    <td>{{ $tanah->kelurahan }}</td>
                                    <td>{{ $tanah->kecamatan }}</td>
                                    <td>{{ number_format($tanah->luas, 2) }}</td>
                                    <td>{{ $tanah->penggunaan }}</td>
                                    <td>{{ $tanah->jenis_tanah }}</td>
                                    <td>{{ $tanah->kadar_air }}</td>
                                    <td>{{ $tanah->lereng }}</td>
                                    <td>{{ $tanah->rekomendasi_tanaman }}</td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                                data-bs-target="#mapModal{{ $tanah->id }}">Lihat Peta</button>
                                            <a href="{{ route('tanah.edit', $tanah->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('tanah.destroy', $tanah->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Peta -->
                                <div class="modal fade" id="mapModal{{ $tanah->id }}" tabindex="-1"
                                    data-id="{{ $tanah->id }}" aria-labelledby="mapModalLabel{{ $tanah->id }}"
                                    aria-hidden="true">
                                    <div
                                        class="modal-dialog modal-fullscreen modal-dialog-centered modal-dialog-scrollable">

                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="mapModalLabel{{ $tanah->id }}">
                                                    Lokasi Tanah - {{ $tanah->nama }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="map{{ $tanah->id }}" style="height: 100vh"></div>
                                                <script type="application/json" id="geojson-data-{{ $tanah->id }}">
                                            {!! $tanah->geojson !!}
                                        </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="16" class="text-center">Belum ada data tanah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>


    <script>
        const leafletMaps = {};

        document.addEventListener('DOMContentLoaded', function() {
            const modals = document.querySelectorAll('.modal');

            modals.forEach(function(modal) {
                modal.addEventListener('shown.bs.modal', function() {
                    const id = modal.getAttribute('data-id');
                    const mapId = `map${id}`;
                    const geojsonEl = document.getElementById(`geojson-data-${id}`);

                    if (!geojsonEl || leafletMaps[id]) return;

                    const geojson = JSON.parse(geojsonEl.textContent);
                    const mapContainer = document.getElementById(mapId);
                    mapContainer.innerHTML = '';

                    if (!navigator.geolocation) {
                        alert("Geolocation tidak didukung browser ini.");
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(function(position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        const userLocation = L.latLng(userLat, userLng);
                        console.log("Lokasi saat ini:",
                            userLocation); // 🔍 Debug di console
                        const map = L.map(mapId).setView(userLocation, 15);
                        leafletMaps[id] = map;

                        const osm = L.tileLayer(
                            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap contributors'
                            }).addTo(map);

                        const satellite = L.tileLayer(
                            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                                attribution: 'Tiles &copy; Esri'
                            });

                        const geoLayer = L.geoJSON(geojson).addTo(map);
                        const bounds = geoLayer.getBounds();

                        if (!bounds.isValid()) {
                            console.warn("GeoJSON bounds tidak valid.");
                            return;
                        }

                        const destination = bounds.getCenter();

                        L.control.layers({
                            "Peta Biasa": osm,
                            "Peta Satelit": satellite
                        }, {
                            "Wilayah Tanah": geoLayer
                        }, {
                            collapsed: false
                        }).addTo(map);

                        map.fitBounds(bounds);

                        // Tambahkan marker lokasi pengguna
                        L.marker(userLocation)
                            .addTo(map)
                            .bindPopup("Lokasi Anda Saat Ini")
                            .openPopup();

                        // Tambahkan routing dari lokasi pengguna ke GeoJSON
                        L.Routing.control({
                            waypoints: [
                                userLocation,
                                destination
                            ],
                            routeWhileDragging: false,
                            draggableWaypoints: true,
                            show: true, // Petunjuk arah muncul
                            collapsible: true, // Bisa ditutup
                            showAlternatives: false
                        }).addTo(map);

                        setTimeout(() => map.invalidateSize(), 300);
                    }, function(error) {
                        alert("Gagal mendapatkan lokasi: " + error.message);
                    });
                });
            });
        });
    </script>
@endsection
