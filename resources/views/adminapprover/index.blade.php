@extends('layouts.adminapprover')
@section('title', 'Approver')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    {{-- <button id="addJabatan" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#userModal">
                        Tambah Karyawan
                    </button> --}}
                    <h5 class="mb-0 mb-3">Data Perjalanan Dinas</h5>
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table id="approverTable" class="table table-striped table-bordered w-100">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Project</th>
                                    <th>Status Perdin</th>
                                    <th>Status Realisasi</th>
                                    <th class="text-center">Aksi</th>
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
        approverData: "{{ route('approver.data') }}",
        approverCategory: "{{ route('approver.getcategoryproduct') }}",
        approverUserPJ: "{{ route('approver.userpj') }}"
    }
</script>
<script src="{{ asset('js/approver.js') }}"></script>
@endsection
