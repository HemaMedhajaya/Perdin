$(document).ready(function () {
    
    
    // Fungsi untuk menghitung total
    function calculateTotal() {
        const biaya = parseFloat($('[name="biaya"]').val()) || 0; 
        const qty = parseFloat($('[name="qty"]').val()) || 0;
        const total = biaya * qty;
        $('[name="total"]').val(total); 
    }

    function getTravelRequestIdFromUrl() {
        const url = window.location.href; 
        const parts = url.split('/'); 
        return parts[parts.length - 1];
    }

    $(document).ready(function () {
        $('#addJabatan').click(function () {
            const travelRequestId = getTravelRequestIdFromUrl();
            $('#travelrequestid').val(travelRequestId);
            $('select[name="jenis_biaya"]').val(''); 
            $('#idrealisasi').val('');
            $('input[name="deskripsi"]').val(''); 
            $('textarea[name="keterangan"]').val(''); 
            $('input[name="biaya"]').val(''); 
            $('input[name="qty"]').val('');
            $('input[name="total"]').val('');
        });
    });
    
    $(document).on('input', '[name="biaya"], [name="qty"]', function () {
        calculateTotal();
    });

    
    // Ambil ID dari URL
    var urlSegments = window.location.pathname.split('/'); 
    var id = urlSegments[urlSegments.length - 1]; 

    var table = $('#tablePerjalanan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/perdin/' + id + '/detail-combined',
            dataSrc: function(json) {
                $('#totalSebelum').text(formatRupiah(json.totalKeseluruhan));
                $('#totalSesudah').text(formatRupiah(json.totalSesudah)); // Menampilkan total setelah realisasi
                return json.data;
            }
        },
        columns: [
            { 
                data: null, 
                name: 'no', 
                orderable: false, 
                searchable: false, 
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + 1; 
                }
            },
            { data: 'jenis_perjalanan_sebelum', name: 'jenis_perjalanan_sebelum' },
            { data: 'description_sebelum', name: 'description_sebelum' },
            { data: 'total_sebelum', name: 'total_sebelum', className: 'text-right',
                render: function(data, type, row) {
                    return data ? formatRupiah(data) : '-';
                }
            },
    
            { 
                data: null, 
                name: 'no', 
                orderable: false, 
                searchable: false, 
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + 1; 
                }
            },
            { data: 'jenis_perjalanan_sesudah', name: 'jenis_perjalanan_sesudah' },
            { data: 'description_sesudah', name: 'description_sesudah' },
            { data: 'total_sesudah', name: 'total_sesudah', className: 'text-right',
                render: function(data, type, row) {
                    return data ? formatRupiah(data) : '-';
                }
            },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ]
    });
    
    function formatRupiah(angka) {
        return 'Rp ' + parseFloat(angka).toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }
    $('#saveBiaya').click(function () {
        var id = $('#idrealisasi').val();
        var url = id ? '/realisasi/' + id : '/realisasi';
        var method = id ? 'PUT' : 'POST'; 
    
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'), 
            travel_request_id: $('#travelrequestid').val(), 
            jenis_biaya: $('select[name="jenis_biaya"]').val(),
            deskripsi: $('input[name="deskripsi"]').val(), 
            keterangan: $('textarea[name="keterangan"]').val(), 
            biaya: $('input[name="biaya"]').val(), 
            qty: $('input[name="qty"]').val(),
            total: $('input[name="total"]').val(),
        };
    
        // Jika method adalah PUT, tambahkan _method
        if (method === 'PUT') {
            data._method = 'PUT'; // Method override untuk Laravel
        }
    
        $.ajax({
            url: url,
            type: 'POST', // Selalu gunakan POST (method override untuk PUT)
            data: data,
            success: function (response) {
                if (response.berhasil) {
                    toastr.success(response.berhasil, "Sukses", {
                        "closeButton": true,
                        "progressBar": true
                    });
                }
                if (response.gagal) {
                    toastr.error(response.gagal, "Error", {
                        "closeButton": true,
                        "progressBar": true
                    });
                }
                $('#biayaModal').modal('hide'); // Tutup modal
                table.ajax.reload(); // Reload tabel (jika menggunakan DataTables)
            },
            error: function (xhr) {
                toastr.error("Terjadi kesalahan!", "Error", {
                    "closeButton": true,
                    "progressBar": true
                });
            }
        });
    });
    

    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
    
        $.get('/realisasi/' + id + '/edit', function (detail) {
            $('#idrealisasi').val(detail.id);
            $('#travelrequestid').val(detail.travel_request_id);
            $('select[name="jenis_biaya"]').val(detail.jenis_perjalanan === 1 ? '1' : '0');
            $('input[name="deskripsi"]').val(detail.transportation); 
            $('textarea[name="keterangan"]').val(detail.description); 
            $('input[name="biaya"]').val(detail.cost); 
            $('input[name="qty"]').val(detail.quantity);
            $('input[name="total"]').val(detail.total); 
        
            $('#biayaModal').modal('show');
        });
    });
    
    function generateTransportasiItem(expense) {
        return `<div class="transportasi-item row position-relative mb-3">
            <div class="col-md-6">
                <label class="form-label">Deskripsi</label>
                <input type="text" name="transportasi[deskripsi]" class="form-control mb-2" value="${expense.transportation}" placeholder="Deskripsi">
                <label class="form-label">Biaya</label>
                <input type="number" name="transportasi[biaya]" class="form-control mb-2" value="${expense.cost}" placeholder="Biaya">
                <label class="form-label">Qty</label>
                <input type="number" name="transportasi[qty]" class="form-control mb-2" value="${expense.quantity}" placeholder="Qty">
            </div>
            <div class="col-md-6">
                <label class="form-label">Total</label>
                <input type="number" name="transportasi[total]" class="form-control mb-2" value="${expense.total}" placeholder="Total" readonly>
                <label class="form-label">Keterangan</label>
                <textarea name="transportasi[keterangan]" class="form-control mb-2" placeholder="Keterangan">${expense.description}</textarea>
                <input type="hidden" name="transportasi[jenis_perjalanan]" value="1">
            </div>
        </div>`;
    }
    
    function generateAkomodasiItem(expense) {
        return `<div class="akomodasi-item row position-relative mb-3">
            <div class="col-md-6">
                <label class="form-label">Deskripsi</label>
                <input type="text" name="akomodasi[deskripsi]" class="form-control mb-2" value="${expense.transportation}" placeholder="Deskripsi">
                <label class="form-label">Biaya</label>
                <input type="number" name="akomodasi[biaya]" class="form-control mb-2" value="${expense.cost}" placeholder="Biaya">
                <label class="form-label">Qty</label>
                <input type="number" name="akomodasi[qty]" class="form-control mb-2" value="${expense.quantity}" placeholder="Qty">
            </div>
            <div class="col-md-6">
                <label class="form-label">Total</label>
                <input type="number" name="akomodasi[total]" class="form-control mb-2" value="${expense.total}" placeholder="Total" readonly>
                <label class="form-label">Keterangan</label>
                <textarea name="akomodasi[keterangan]" class="form-control mb-2" placeholder="Keterangan">${expense.description}</textarea>
                <input type="hidden" name="akomodasi[jenis_perjalanan]" value="0">
            </div>
        </div>`;
    }

    $(document).on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        $('#deleterealisasi').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function () {
        var id = $('#deleterealisasi').val();
        $.ajax({
            url: '/realisasi/' + id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.berhasil) {
                    toastr.success(response.berhasil, "Sukses", {
                        "closeButton": true,
                        "progressBar": true
                    });
                }
                if (response.gagal) {
                    toastr.error(response.gagal, "Error", {
                        "closeButton": true,
                        "progressBar": true
                    });
                }
                $('#deleteModal').modal('hide');
                table.ajax.reload();
            }
        });
    });

});