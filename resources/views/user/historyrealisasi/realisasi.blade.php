@extends('layouts.user')
@section('title', 'Perjalanan Dinas')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Perjalanan Dinas Setelah Realisasi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablePerjalanan" class="table table-striped table-bordered w-100">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Deskripsi</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">TOTAL KESELURUHAN</th>
                                    <th class="text-right" id="totalSesudah"></th>
                                    <th></th>
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
                <h5 class="modal-title">Detail</h5>
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
                        <input type="hidden" name="idrealisasi" id="idrealisasi">
                        <input type="hidden" data-id="1" name="status_approve" id="status_approve">
                        <label class="form-label">Deskripsi</label>
                        <input type="text" name="deskripsi" class="form-control mb-2" placeholder="Deskripsi" readonly>
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control mb-2" placeholder="Keterangan" readonly></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Biaya</label>
                        <input type="number" name="biaya" class="form-control mb-2" placeholder="Biaya" readonly>
                        <label class="form-label">Qty</label>
                        <input type="number" name="qty" class="form-control mb-2" placeholder="Qty" readonly>
                        <label class="form-label">Total</label>
                        <input type="number" name="total" class="form-control mb-2" placeholder="Total" readonly>
                    </div>
                </div>
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
                <input type="hidden" id="deleterealisasi">
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
<script src="{{ asset('js/historyrealisasidetail.js') }}" ></script>
@endsection
