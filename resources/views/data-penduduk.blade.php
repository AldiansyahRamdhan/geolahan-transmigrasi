@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            min-height: 100vh;
            overflow-y: auto;
        }
    </style>
    <div class="container py-4">
        <h5 class="mb-4" style="margin-top:70px;">Daftar Tanah</h5>

        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control"
                placeholder="Cari berdasarkan NIB, Desa, atau Jenis Tanah">
        </div>


        <div class="row g-4">
            @forelse ($tanahs as $tanah)
                <div class="col-md-6 col-lg-4 tanah-card" data-kecamatan="{{ strtolower($tanah->kecamatan) }}"
                    data-desa="{{ strtolower($tanah->kelurahan) }}" data-nib="{{ strtolower($tanah->nib) }}"
                    data-rekom="{{ strtolower($tanah->rekomendasi_tanaman) }}">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">

                            <table class="table table-borderless table-sm mb-3">
                                <tbody>
                                    <tr>
                                        <th scope="row">Kecamatan</th>
                                        <td>: {{ $tanah->kecamatan }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Kelurahan</th>
                                        <td>: {{ $tanah->kelurahan }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tipe Hak</th>
                                        <td>: {{ $tanah->tipe_hak }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tahun</th>
                                        <td>: {{ date('Y', strtotime($tanah->tahun)) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">NIB</th>
                                        <td>: {{ $tanah->nib }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Luas Terdaftar</th>
                                        <td>: {{ number_format($tanah->luas, 2) }} m²</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Penggunaan</th>
                                        <td>: {{ $tanah->penggunaan }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Jenis Tanah</th>
                                        <td>: {{ $tanah->jenis_tanah }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Kadar Air</th>
                                        <td>: {{ $tanah->kadar_air }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Lereng</th>
                                        <td>: {{ $tanah->lereng }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Rekomendasi</th>
                                        <td>: {{ $tanah->rekomendasi_tanaman }}</td>
                                    </tr>



                                </tbody>
                            </table>


                            <button class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal"
                                data-bs-target="#mapModal{{ $tanah->id }}">
                                Lihat Peta
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Modal Peta -->
                <div class="modal fade p-0 m-0" id="mapModal{{ $tanah->id }}" tabindex="-1"
                    data-id="{{ $tanah->id }}" aria-labelledby="mapModalLabel{{ $tanah->id }}" aria-hidden="true"
                    style="z-index: 999999">
                    <div class="modal-dialog modal-fullscreen"> {{-- Ubah jadi fullscreen --}}
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="mapModalLabel{{ $tanah->id }}">
                                    Lokasi Tanah - {{ $tanah->nama }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0"> {{-- tanpa padding untuk fullscreen map --}}
                                <div id="map{{ $tanah->id }}" style="height: 100vh;"></div>
                                <script type="application/json" id="geojson-data-{{ $tanah->id }}">
                    {!! $tanah->geojson !!}
                </script>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">Belum ada data tanah.</div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll('.tanah-card');

            cards.forEach(card => {
                const kecamatan = card.dataset.kecamatan;
                const desa = card.dataset.desa;
                const nib = card.dataset.nib;
                const rekom = card.dataset.rekom;

                const isMatch = kecamatan.includes(query) || desa.includes(query) || nib.includes(query) ||
                    rekom.includes(query);
                card.style.display = isMatch ? 'block' : 'none';
            });
        });
    </script>


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

                    // Init map (sementara dummy koordinat)
                    const map = L.map(mapId).setView([-7.26, 106.91], 15);
                    leafletMaps[id] = map;

                    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    const satellite = L.tileLayer(
                        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                            attribution: 'Tiles © Esri'
                        });

                    const geoLayer = L.geoJSON(geojson).addTo(map);
                    const bounds = geoLayer.getBounds();

                    if (bounds.isValid()) {
                        map.fitBounds(bounds);
                    }

                    const destination = bounds.getCenter();

                    // Layer Switcher
                    L.control.layers({
                        "Peta Biasa": osm,
                        "Peta Satelit": satellite
                    }, {
                        "Wilayah Tanah": geoLayer
                    }).addTo(map);

                    // Ambil lokasi pengguna (Geolocation)
                    if (!navigator.geolocation) {
                        alert("Browser tidak mendukung geolokasi.");
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(function(position) {
                        const userLatLng = L.latLng(position.coords.latitude, position
                            .coords.longitude);

                        // Tambahkan marker lokasi pengguna
                        L.marker(userLatLng).addTo(map).bindPopup("Lokasi Anda")
                            .openPopup();

                        // Tambahkan Routing
                        L.Routing.control({
                            waypoints: [
                                userLatLng,
                                destination
                            ],
                            routeWhileDragging: false,
                            draggableWaypoints: false,
                            showAlternatives: false,
                            collapsible: true,
                            show: true,
                            createMarker: function(i, waypoint, n) {
                                return L.marker(waypoint.latLng).bindPopup(i ===
                                    0 ? "Anda di sini" : "Lokasi Tanah");
                            }
                        }).addTo(map);

                        setTimeout(() => {
                            map.invalidateSize();
                            map.setZoom(15);
                        }, 300);
                    }, function(error) {
                        alert("Gagal mendapatkan lokasi: " + error.message);
                    });
                });
            });
        });
    </script>
@endsection
