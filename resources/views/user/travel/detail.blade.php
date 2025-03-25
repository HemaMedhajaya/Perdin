@extends('layouts.user')
@section('title', 'Perjalanan Dinas')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-4 order-0">
                <div class="card">
                    <div class="card-body">

                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-project-diagram" style="font-size: 24px; color: #4CAF50;"></i>
                                    <div>
                                        <strong class="text-dark font-weight-bold" style="font-size: 14px;">Nama Project</strong>
                                        <h6 class="card-title">{{ $nameprojec }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top: 10px;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-tie" style="font-size: 24px; color: #2196F3;"></i>
                                    <div>
                                        <strong class="text-dark font-weight-bold" style="font-size: 14px;">Nama Penanggung Jawab</strong>
                                        <h6 class="card-title">{{ $nameuser }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <hr class="mt-4">
                        <div class="col-12 d-flex justify-content-between">
                            <div>
                                <button id="addJabatan" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#biayaModal">
                                    Tambah Biaya
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('export.kasbon.excel', ['id' => $id]) }}" class="btn btn-outline-success mb-3">
                                    <i class="bx bxs-file-export"></i>
                                </a>
                                <a href="{{ route('export.kasbon.pdf', ['id' => $id]) }}" class="btn btn-outline-danger mb-3">
                                    <i class='bx bxs-file-pdf'></i>
                                </a>
                                <button id="requestButton" class="request-btn btn btn-success mb-3" data-id="" data-bs-toggle="modal" data-bs-target="#submitRequestModal">
                                    Submit Request
                                </button>
                            </div>
                        </div>                     
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table id="detailTable" class="table table-striped table-bordered w-100">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Jenis</th>
                                        <th>Deskripsi</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="odd">
                                        <th colspan="3" class="text-right">TOTAL KESELURUHAN</th>
                                        <th class="text-right" id="totalKeseluruhan"></th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="biayaModal" tabindex="-1" aria-labelledby="biayaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Biaya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="biaya-item row">
                        <div class="col-md-6">
                            <label class="form-label">Jenis Biaya</label>
                            <select name="jenis_biaya" class="form-control mb-2">
                                <option value="">Pilih Biaya</option>
                                <option value="1">Transportasi</option>
                                <option value="0">Akomodasi</option>
                            </select>
                            <input type="hidden" name="travelrequestid" id="travelrequestid">
                            <input type="hidden" name="idexpenses" id="idexpenses">
                            <input type="hidden" data-id="1" name="status_approve" id="status_approve">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="deskripsi" class="form-control mb-2" placeholder="Deskripsi">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control mb-2" placeholder="Keterangan"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Biaya</label>
                            <input type="number" name="biaya" id="biaya" class="form-control mb-2" placeholder="Biaya">
                            <label class="form-label">Qty</label>
                            <input type="number" name="qty" class="form-control mb-2" placeholder="Qty">
                        <label class="form-label">Man</label>
                            <input type="number" name="man" class="form-control mb-2" placeholder="Man">
                            <label class="form-label">Total</label>
                            <input type="number" name="total" class="form-control mb-2" placeholder="Total" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button id="saveBiaya" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="biayadeleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus user ini?</p>
                    <input type="hidden" id="deletedetail">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button id="confirmDelete" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Submit Request --}}
    <div class="modal fade" id="submitRequestModal" tabindex="-1" aria-labelledby="submitRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitRequestModalLabel">Konfirmasi Submit Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengirim request ini?</p>
                    <input type="hidden" id="requestId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button id="confirmSubmit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('script')
    <script src="{{ asset('js/detail.js') }}"></script>
@endsection
