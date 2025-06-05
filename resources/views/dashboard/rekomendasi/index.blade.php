@extends('dashboard.layouts.main')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
    <div class="container py-4">



        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="card-title">
                        <h5>Daftar Rekomendasi Tanaman</h5>
                    </div>
                    <a href="{{ route('rekomendasi.create') }}" class="btn btn-primary">+ Tambah Data</a>
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" id="success-alert">
                        {{ session('success') }}
                    </div>

                    <script>
                        setTimeout(() => {
                            const alert = document.getElementById('success-alert');
                            if (alert) {
                                alert.classList.remove('show');
                                alert.classList.add('fade');
                                setTimeout(() => alert.remove(), 300); // Hapus elemen dari DOM
                            }
                        }, 2000); // 2 detik
                    </script>
                @endif

                <div class="table-responsive">
                    <table class="table datatable align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Penggunaan</th>
                                <th>Jenis&nbsp;Tanah</th>
                                <th>Kadar&nbsp;Air</th>
                                <th>Lereng</th>
                                <th>Rekomendasi&nbsp;Tanaman</th>

                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @forelse ($rekomendasis as $index => $rekomen)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $rekomen->penggunaan }}</td>
                                    <td>{{ $rekomen->jenis_tanah }}</td>
                                    <td>{{ $rekomen->kadar_air }}</td>
                                    <td>{{ $rekomen->lereng }}</td>
                                    <td>{{ $rekomen->rekomendasi_tanaman }}</td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('rekomendasi.edit', $rekomen->id) }}"
                                                class="btn btn-sm btn-warning me-3">Edit</a>
                                            <form action="{{ route('rekomendasi.destroy', $rekomen->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>


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
@endsection
