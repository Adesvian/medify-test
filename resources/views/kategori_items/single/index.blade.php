@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        @if (session('info'))
                            <br><small>{{ session('info') }}</small>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <div class="d-flex justify-content-between mb-1">
                    <div class="form-group mb-2">
                        <a href="{{ url('kategori-items') }}" class="btn btn-secondary">Kembali ke Daftar Item</a>
                    </div>
                    <a href="{{ url('kategori-items/export-pdf/' . $data->id) }}"
                        class="btn btn-outline-danger align-self-center">Export as
                        PDF</a>
                </div>
                <div class="card">
                    <div class="card-header">Kategori Item</div>

                    <div class="card-body">
                        <table>
                            <tr>
                                <th>Kode</th>
                                <td>:</td>
                                <td>{{ $data->kode }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>:</td>
                                <td>{{ $data->nama }}</td>
                            </tr>
                        </table>
                        <a class="btn btn-info" href="{{ url('kategori-items/form/edit') }}/{{ $data->id }}">Edit</a>
                        <a class="btn btn-danger" href="{{ url('kategori-items/delete') }}/{{ $data->id }}"
                            onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        <h3 class="mt-3">Daftar data master yang berkategori {{ $data->nama }}</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>view</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->master as $item)
                                    <tr>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>
                                            <a href="{{ url('master-items/view/') }}/{{ $item->kode }}"
                                                class="btn btn-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
