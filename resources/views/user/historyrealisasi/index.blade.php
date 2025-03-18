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
                    {{-- <hr class="mt3"> --}}
                    {{-- <div class="mb-2">
                        <div id="transportasi-container">
                            <div class="transportasi-item row">
                                <div class="col-md-6">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <input type="text" name="transportasi[deskripsi][]" class="form-control mb-2" placeholder="Deskripsi" id="deskripsi">
                                    
                                    <label for="biaya" class="form-label">Biaya</label>
                                    <input type="number" name="transportasi[biaya][]" class="form-control mb-2" placeholder="Biaya" id="biaya">
                                    
                                    <label for="qty" class="form-label">Qty</label>
                                    <input type="number" name="transportasi[qty][]" class="form-control mb-2" placeholder="Qty" id="qty">
                                </div>
                                <div class="col-md-6">
                                    <label for="total" class="form-label">Total</label>
                                    <input type="number" name="transportasi[total][]" class="form-control mb-2" placeholder="Total" readonly id="total">
                                    
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea name="transportasi[keterangan][]" class="form-control mb-2" placeholder="Keterangan" id="keterangan"></textarea>
                                    
                                    <input type="hidden" name="transportasi[jenis_perjalanan][]" value="1">
                                </div>
                            </div>
                        </div>
                        <button type="button" id="addTransportasi" class="btn btn-primary mt-3">Tambah Transportasi</button>
                    </div>
                    <div class="mb-2">
                        <div id="akomodasi-container">
                            <div class="akomodasi-item row">
                                <div class="col-md-6">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <input type="text" name="akomodasi[deskripsi][]" class="form-control mb-2"
                                        placeholder="Deskripsi">
                                    <label for="biaya" class="form-label">Biaya</label>
                                    <input type="number" name="akomodasi[biaya][]" class="form-control mb-2"
                                        placeholder="Biaya">
                                    <label for="qty" class="form-label">Qty</label>
                                    <input type="number" name="akomodasi[qty][]" class="form-control mb-2"
                                        placeholder="Qty">
                                </div>
                                <div class="col-md-6">
                                    <label for="total" class="form-label">Total</label>
                                    <input type="number" name="akomodasi[total][]" class="form-control mb-2"
                                        placeholder="Total" readonly>
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea name="akomodasi[keterangan][]" class="form-control mb-2" placeholder="Keterangan"></textarea>
                                    <input type="hidden" name="akomodasi[jenis_perjalanan][]" value="0">
                                    <!-- Status Akomodasi -->
                                </div>
                            </div>
                        </div>
                        <button type="button" id="addAkomodasi" class="btn btn-primary mt-3">Tambah Akomodasi</button>
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
                    <input type="hidden" id="deleteperdinid">
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
        historyrealisasiData: "{{ route('historyrealisasi.data') }}",
        perdinDataUser: "{{ route('perdin.datauser') }}",
        perdinCategory: "{{ route('perdin.getcategoryproduct') }}",
        perdinUserPJ: "{{ route('perdin.userpj') }}"
    };
    </script>
    <script src="{{ asset('js/historyrealisasi.js') }}"></script>
@endsection
