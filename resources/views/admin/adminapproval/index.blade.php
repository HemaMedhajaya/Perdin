@extends('layouts.admin')
@section('title', 'Jabatan')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-0 mb-3">Data Admin Approve</h5>
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table id="usersTable" class="table table-striped table-bordered w-100">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
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


<!-- Modal Tambah/Edit -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Form Admin Approve</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="adminapproveid">
                <div class="mb-2">
                    <input type="text" id="name" class="form-control" placeholder="Nama">
                </div>
                <div class="mb-2">
                    <select name="type" id="udf1" class="form-select">
                    </select>
                </div>
                <div class="mb-2">
                    <select name="type" id="udf2" class="form-select">
                    </select>
                </div>
                <div class="mb-2">
                    <select name="type" id="udf3" class="form-select">
                    </select>
                </div>
                <div class="mb-2">
                    <select name="type" id="udf4" class="form-select">
                    </select>
                </div>
                <div class="mb-2">
                    <select name="type" id="udf5" class="form-select">
                    </select>
                </div>
                <div class="mb-2">
                    <select name="type" id="udf6" class="form-select">
                    </select>
                </div>
                <div class="mb-2">
                    <select name="type" id="udf7" class="form-select">
                    </select>
                </div>
                <div class="mb-2">
                    <select name="type" id="udf8" class="form-select">
                    </select>
                </div>
                <div class="mb-2">
                    <select name="type" id="udf9" class="form-select">
                    </select>
                </div>
                <div class="mb-2">
                    <select name="type" id="udf10" class="form-select">
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button id="saveUser" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user ini?</p>
                <input type="hidden" id="deleteadminapproveid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button id="confirmDelete" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var routes = {
        adminapprovalData: "{{ route('adminapproval.data') }}",
    }
</script>
<script src="{{ asset('js/adminapproval.js') }}"></script>
@endsection
