@extends('layouts.user')
@section('title', 'Perjalanan Dinas')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-4 order-0">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-0 mb-3">Data Sudah Realisasi</h5>
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table id="perdiTable" class="table table-striped table-bordered w-100">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Project</th>
                                        <th>Keperluan</th>
                                        <th>Lokasi Kerja</th>
                                        <th>Status Approve</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        var routes = {
        historyrealisasiData: "{{ route('historyrealisasi.data') }}",
        perdinDataUser: "{{ route('perdin.datauser') }}",
        perdinCategory: "{{ route('perdin.getcategoryproduct') }}",
        perdinUserPJ: "{{ route('perdin.userpj') }}"
    };
    </script>
    <script src="{{ asset('js/historyrealisasi.js') }}"></script>
@endsection
