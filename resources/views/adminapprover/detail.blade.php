@extends('layouts.adminapprover')
@section('title', 'Perjalanan Dinas')
@section('content')
@include('layouts.loading')
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
                        <div class="col-12 d-flex justify-content-end">
                            <div class="m-1">
                                <a href="{{ route('export.kasbon.admin.excel', ['id' => $id]) }}" class="btn btn-outline-success">
                                    <i class="bx bxs-file-export"></i>
                                </a>
                            </div>
                            <div class="m-1">
                                
                                <a href="{{ route('export.kasbon.admin.pdf', ['id' => $id]) }}" class="btn btn-outline-danger ml-2">
                                    <i class='bx bxs-file-pdf'></i>
                                </a>
                            </div>
                        </div>
                                           
                        <div class="col-12 d-flex justify-content-between">
                            <div>
                            </div>
                            <div>
                                <button id="reject" class="request-btn btn btn-danger mb-3" data-id="{{ $id }}" data-bs-toggle="modal" data-bs-target="#komentar">
                                    Reject
                                </button>
                                <button id="approve" class="request-btn btn btn-success mb-3" data-id="{{ $id }}" data-bs-toggle="modal" data-bs-target="#approverModal">
                                    Approve
                                </button>
                            </div>
                        </div>   
                        <input type="hidden" id='idtravelrequest' value="{{ $id }}">                  
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table id="detailTable" class="table table-striped table-bordered w-100">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Jenis</th>
                                        <th>Deskripsi</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
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
                            <select name="jenis_biaya" class="form-control mb-2" disabled>
                                <option value="">Pilih Biaya</option>
                                <option value="1">Transportasi</option>
                                <option value="0">Akomodasi</option>
                            </select>
                            <input type="hidden" name="idexpenses" id="idexpenses">
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
                        <label class="form-label">Man</label>
                            <input type="number" name="man" class="form-control mb-2" placeholder="Man" readonly>
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

    {{-- Modal konfirmasi tolak --}}
    <div class="modal fade" id="komentar" tabindex="-1" aria-labelledby="komentarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="komentarLabel">Alasan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="travelrequestid">
                    <div class="mb-2">
                        <textarea id="komentarText" class="form-control" placeholder="Masukkan alasan penolakan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button id="RejectPerdin" data-id="{{$id}}" data-status="2" class="btn btn-danger action-button">Tolak</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approve -->
    <div class="modal fade" id="approverModal" tabindex="-1" aria-labelledby="approverModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approverModalLabel">Konfirmasi Persetujuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="travelrequestidapprove">
                    <p>Apakah Anda yakin ingin menyetujui permintaan ini?</p>
                    <input type="hidden" id="approverjabatanid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button id="confirmapprover" data-id="{{$id}}" data-status="1" class="btn btn-success action-button">Setujui</button>
                </div>
            </div>
        </div>
    </div>

    
@endsection

@section('script')
<script src="{{ asset('js/detailapprover.js') }}" ></script>
@endsection
