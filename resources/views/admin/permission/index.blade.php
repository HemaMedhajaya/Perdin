@extends('layouts.admin')
@section('title', 'Permission')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    <button id="addPermission" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#permissionModal">
                        Tambah Permission
                    </button>
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table id="roleTable" class="table table-striped table-bordered w-100">
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
<div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionModalLabel">Form Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="permissionid">
                <div class="mb-2">
                    <select name="submenu_id" id="submenu_id" class="form-select">
                        @foreach ($submenu as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->name }} - 
                                @if($s->type == 1)
                                    Admin
                                @elseif($s->type == 2)
                                    Admin Approver
                                @elseif($s->type == 0)
                                    User
                                @endif
                            </option> 
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <input type="text" id="name" class="form-control" placeholder="Nama">
                </div>
                <div class="mb-2">
                    <input type="text" id="slug" class="form-control" placeholder="Nama">
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
                <input type="hidden" id="deletepermissionid">
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
        permissionnData: "{{ route('permission.data') }}"
    }
</script>
<script src="{{ asset('js/permission.js') }}"></script>
@endsection
