$(document).ready(function () {
    
    
    // Fungsi untuk menghitung total
    function calculateTotal() {
        const biaya = parseFloat($('[name="biaya"]').val()) || 0; 
        const qty = parseFloat($('[name="qty"]').val()) || 0;
        const man = parseFloat($('[name="man"]').val()) || 0;
        const total = biaya * qty * man;
        $('[name="total"]').val(total); 
    }

    function getTravelRePquestIdFromUrl() {
        const url = window.location.href; 
        const parts = url.split('/'); 
        return parts[parts.length - 1];
    }

    $(document).ready(function () {
        $('#addJabatan').click(function () {
            const travelRequestId = getTravelRePquestIdFromUrl();
            $('#travelrequestid').val(travelRequestId);
            $('select[name="jenis_biaya"]').val(''); 
            $('#idrealisasi').val('');
            $('input[name="deskripsi"]').val(''); 
            $('textarea[name="keterangan"]').val(''); 
            $('input[name="biaya"]').val(''); 
            $('input[name="qty"]').val('');
            $('input[name="total"]').val('');
            $('input[name="man"]').val('');
        });
    });
    
    $(document).on('input', '[name="biaya"], [name="qty"], [name="man"]', function () {
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
                console.log(json.status_approve_realisasi);
                $('#totalSebelum').text(formatRupiah(json.totalKeseluruhan));
                $('#totalSesudah').text(formatRupiah(json.totalSesudah));
                if (json.status_approve_realisasi == 2 || json.status_approve_realisasi == 4) {
                    table.column(9).visible(false);
                } else {
                    table.column(9).visible(true);
                }
            
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
            { data: 'status_approve_realisasi', name: 'status_approve_realisasi', orderable: false, searchable: false, className: 'text-center' },
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
            man: $('input[name="man"]').val(),
        };
    
        if (method === 'PUT') {
            data._method = 'PUT'; 
        }
    
        $.ajax({
            url: url,
            type: 'POST', 
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
                $('#biayaModal').modal('hide'); 
                table.ajax.reload(); 
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
            $('input[name="man"]').val(detail.man); 
        
            $('#biayaModal').modal('show');
        });
    });
    
    $.ajax({
        url: '/statusapprovedetail/' + id,  
        type: 'GET',
        success: function (response) {
            if (response.status_approve_realisasi == 2 || response.status_approve_realisasi == 4) {
                $('#addJabatan').addClass('hiddenbutton')
            } else {
                $('#addJabatan').removeClass('hiddenbutton');
            }
            updateButton(response.status_approve_realisasi); 
        }
    });

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

    $('#saveData').click(function () {
        var idtravel = $(this).attr('data-id');
        console.log(idtravel);
        $('#requestId').val(idtravel);
        $('#submitRequestModal').modal('show');
    })

    $('#confirmSubmit').click(function () {
        var idreques = $('#requestId').val();
        var idtravelrequest = $(this).attr('data-idrequesttravel');
        console.log(idtravelrequest);
        console.log(2);
        $.ajax({
            url: '/statusapprove/realisasi/' + idtravelrequest,
            type:'PUT',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                idrequest: idreques,
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
                $('#submitRequestModal').modal('hide');
                table.ajax.reload();
                updateButton(response.status_approve_realisasi);
                console.log(response.status_approve_realisasi)
                if (response.status_approve_realisasi == 2) {
                    $('#addJabatan').addClass('hiddenbutton')
                } else {
                    $('#addJabatan').removeClass('hiddenbutton');
                }
            }
        });
    })

    function updateButton(status) {
        var button = $('#saveData');
        console.log(status);
        console.log('sampai');
        console.log('uptdae button');
        if (status == 1 || status == 3) {
            console.log('uptdae button submit')
            button.text('Submit Request')
                .removeClass('btn-danger')
                .addClass('btn-success')
                .attr('data-id', '2');
        } else if (status == 4) {
            button.addClass('hiddenbutton')
        } else{
            console.log('uptdae button cencel')
            button.text('Cancel Request')
                .removeClass('btn-success')
                .addClass('btn-danger')
                .attr('data-id', '1');
        }
    }

});