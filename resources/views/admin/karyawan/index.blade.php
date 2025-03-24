@extends('layouts.admin')
@section('title', 'Karyawans')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    @if ($data['permissionAddKaryawan'] > 0)
                        <button id="addKarywans" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#userModal">Tambah Karyawan</button>
                    @endif
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table id="usersTable" class="table table-striped table-bordered w-100">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Departement</th>
                                    <th>Jabatan</th>
                                    <th>Status</th>
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
                <h5 class="modal-title" id="userModalLabel">Form Karywan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="karwanid">
                {{-- <div class="mb-2">
                    <input type="text" id="name" class="form-control" placeholder="Nama">
                </div>
                <div class="mb-2">
                    <input type="email" id="email" class="form-control" placeholder="Email">
                </div> --}}
                <div class="mb-2">
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">Pilih User</option>
                        @foreach ($user as $u )
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <select name="departement_id" id="departement_id" class="form-select">
                        <option value="">Pilih Departement</option>
                        @foreach ($departement as $d )
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <select name="jabatan_id" id="jabatan_id" class="form-select">
                        <option value="">Pilih Jabatan</option>
                        @foreach ($jabatan as $j )
                            <option value="{{ $j->id }}">{{ $j->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <select name="status_user" id="status_user" class="form-select">
                        <option value="">Pilih Status Pengguna</option>
                        <option value="1">Akses</option>
                        <option value="0">Non Akses</option>
                    </select>
                </div>
                <div class="mb-2">
                    <input type="text" id="nik" class="form-control" placeholder="Nomor Induk Kependudukan">
                </div>
                <div class="mb-2">
                    <input type="text" id="nomortlp" class="form-control" placeholder="Nomor Telepon">
                </div>
                {{-- <div class="mb-2">
                    <input type="password" id="password" class="form-control" placeholder="Password">
                </div> --}}
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
                <input type="hidden" id="deletekarwanid">
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
        karyawansData: "{{ route('karyawans.data') }}",
    }
</script>
<script src="{{ asset('js/karyawan.js') }}"></script>
@endsection
