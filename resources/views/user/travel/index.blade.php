@extends('layouts.user')
@section('title', 'Perjalanan Dinas')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-4 order-0">
                <div class="card">
                    <div class="card-body">
                        @if ($data['permissionAddPerjalananDinas'] > 0)
                        <button id="addPerdin" class="btn btn-primary mb-3" data-bs-toggle="modal"
                            data-bs-target="#userModal">Tambah Perdin</button>
                        @endif
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table id="perdiTable" class="table table-striped table-bordered w-100">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Project</th>
                                        <th>Status Perdin</th>
                                        <th>Status Realisasi</th>
                                        <th>Aksi</th>
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Form Perjalanan Dinas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="perdinid">

                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="name" class="form-label">Nama Pemohon</label>
                                <input type="text" id="name" class="form-control" placeholder="Nama" readonly>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="departement_id" class="form-label">Departement</label>
                                <input type="text" id="departement_id" class="form-control" placeholder="Departement"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="jabatan_id" class="form-label">Jabatan</label>
                                <input type="text" id="jabatan_id" class="form-control" placeholder="Jabatan" readonly>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="nomortlp" class="form-label">Nomor Telepon</label>
                                <input type="nomortlp" id="nomortlp" class="form-control" placeholder="Nomor Telepon" readonly>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-3">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="projectname" class="form-label">Project</label>
                                <input type="text" id="projectname" name="projectname" class="form-control" placeholder="Nama Project">
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="nomorso" class="form-label">Nomor So</label>
                                <input type="text" id="nomorso" name="nomorso" class="form-control" placeholder="Nomor So">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="lokasikerja" class="form-label">Lokasi Kerja</label>
                                <input type="text" id="lokasikerja" name="lokasikerja" class="form-control" placeholder="Lokasi Kerja">
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="keperluan" class="form-label">Keperluan</label>
                                <input type="text" id="keperluan" name="keperluan" class="form-control" placeholder="Keperluan">
                            </div>
                        </div>
                    </div>
                    <hr class="mt-3">
                    <div class="">
                        <label class="form-label">Pilih Category Product:</label>
                        <div id="category-list" class="form-check mt-2"></div>
                    </div>
                    <hr class="mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Pilih Peserta Perjalan Dinas:</label>
                                <select name="user_id[]" id="user_id" class="form-select" multiple></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Pilih Penanggung Jawab:</label>
                                <select name="userpj_id[]" id="userpj_id" class="form-select" multiple></select>
                            </div>
                        </div>
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
                    <input type="hidden" id="deleteperdinid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button id="confirmDelete" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="komentarshow" tabindex="-1" aria-labelledby="komentarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="komentarLabel">Alasan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="travelrequestid">
                    <div class="mb-2">
                        <textarea id="komentarText" class="form-control" placeholder="Masukkan alasan penolakan" readonly></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var routes = {
        perdinData: "{{ route('perdin.data') }}",
        perdinDataUser: "{{ route('perdin.datauser') }}",
        perdinCategory: "{{ route('perdin.getcategoryproduct') }}",
        perdinUserPJ: "{{ route('perdin.userpj') }}"
    };
    </script>
    <script src="{{ asset('js/travel.js') }}"></script>
@endsection
