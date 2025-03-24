@extends('layouts.admin')
@section('title', 'Sub Menu')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    <button id="addSubmenu" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#userModal">
                        Tambah Submenu
                    </button>
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table id="submenuTable" class="table table-striped table-bordered w-100">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Route</th>
                                    <th>Menu Induk</th>
                                    <th>Type</th>
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
                <h5 class="modal-title" id="userModalLabel">Form Submenu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="submenusid">
                <div class="mb-2">
                    <input type="text" id="name" class="form-control" placeholder="Nama">
                </div>
                <div class="mb-2">
                    <input type="text" id="route" class="form-control" placeholder="Route">
                </div>
                <div class="mb-2">
                    <select name="is_parent" id="is_parent" class="form-select">
                        <option value="">Menu Type</option>
                        <option value="1">Master Menu</option>
                        <option value="0">Standalone Menu</option>
                    </select>
                </div>
                <div class="mb-2">
                    <input type="text" id="icon" class="form-control" placeholder="Icon">
                </div>
                <div class="mb-2">
                    <select name="type" id="menu_id" class="form-select">
                    </select>
                </div>
                <div class="mb-2">
                    <select name="type" id="type" class="form-select">
                        <option value="">Pilih Type</option>
                        <option value="0">User</option>
                        <option value="1">Admin</option>
                        <option value="2">Admin Approver</option>
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
                <input type="hidden" id="deletesubmenusid">
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
        submenusData: "{{ route('submenus.data') }}",
        menusData: "{{ route('submenus.menus') }}"
    }
</script>
<script src="{{ asset('js/submenu.js') }}"></script>
@endsection
