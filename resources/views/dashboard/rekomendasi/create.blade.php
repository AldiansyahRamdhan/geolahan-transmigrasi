@extends('dashboard.layouts.main')

@section('content')
    <div class="container py-4">
        <div class="col-lg-5 mx-auto">


            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Tambah Data Tanah</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('rekomendasi.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="penggunaan" class="form-label">Penggunaan</label>
                            <input type="text" class="form-control" id="penggunaan" name="penggunaan"
                                value="{{ old('penggunaan') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_tanah" class="form-label">Jenis Tanah</label>
                            <input type="text" class="form-control" id="jenis_tanah" name="jenis_tanah"
                                value="{{ old('jenis_tanah') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="kadar_air" class="form-label">Kadar Air</label>
                            <input type="text" class="form-control" id="kadar_air" name="kadar_air"
                                value="{{ old('kadar_air') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="lereng" class="form-label">Lereng</label>
                            <input type="text" class="form-control" id="lereng" name="lereng"
                                value="{{ old('lereng') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="rekomendasi_tanaman" class="form-label">Rekomendasi Tanaman</label>
                            <input type="text" class="form-control" id="rekomendasi_tanaman" name="rekomendasi_tanaman"
                                value="{{ old('rekomendasi_tanaman') }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('rekomendasi.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
