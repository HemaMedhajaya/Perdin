$(document).ready(function () {
    function calculateTotal() {
        const biaya = parseFloat($('[name="biaya"]').val()) || 0; 
        const qty = parseFloat($('[name="qty"]').val()) || 0;
        const man = parseFloat($('[name="man"]').val()) || 0;
        const total = biaya * qty * man;
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
            $('#idexpenses').val('');
            $('input[name="deskripsi"]').val(''); 
            $('textarea[name="keterangan"]').val(''); 
            $('input[name="biaya"]').val(''); 
            $('input[name="qty"]').val('');
            $('input[name="man"]').val('');
            $('input[name="total"]').val('');
        });
    });
    
    $(document).on('input', '[name="biaya"], [name="qty"], [name="man"]', function () {
        calculateTotal();
    });
    // Ambil ID dari URL
    var urlSegments = window.location.pathname.split('/'); 
    var id = urlSegments[urlSegments.length - 1]; 

    var table = $('#detailTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/perdin/' + id + '/detail',
            dataSrc: function(json) {
                $('#totalKeseluruhan').text(formatRupiah(json.totalKeseluruhan));
    
                if (json.status_approve == 5 || json.status_approve == 1 || json.status_approve == 4 || json.status_approve == 6) {
                    table.column(5).visible(false);
                } else {
                    table.column(5).visible(true); 
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
            { data: 'jenis_perjalanan', name: 'jenis_perjalanan' },
            { data: 'description', name: 'description' },
            { 
                data: 'total', 
                name: 'total', 
                className: 'text-right',
                render: function(data, type, row) {
                    return formatRupiah(data);
                }
            },
            { data: 'status_approve', name: 'status_approve', orderable: false, searchable: false, className: 'text-center' },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false, 
                className: 'text-center'
            }
        ]
    });
    function formatRupiah(angka) {
        return 'Rp ' + parseFloat(angka).toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }
    
    

    $('#saveBiaya').click(function () {
        var id = $('#idexpenses').val();
        var url = id ? '/detail/' + id : '/detail';
        var method = id ? 'PUT' : 'POST';
        $('#biayaModal').modal('hide');
        $('#loadingOverlay').fadeIn();
    
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            travel_request_id: $('#travelrequestid').val(),
            jenis_biaya: $('select[name="jenis_biaya"]').val(),
            deskripsi: $('input[name="deskripsi"]').val(),
            keterangan: $('textarea[name="keterangan"]').val(),
            biaya: $('input[name="biaya"]').val(),
            qty: $('input[name="qty"]').val(),
            man: $('input[name="man"]').val(),
            total: $('input[name="total"]').val(),
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
                table.ajax.reload();
            },
            error: function (xhr) {
                toastr.error("Terjadi kesalahan!", "Error", {
                    "closeButton": true,
                    "progressBar": true
                });
            },
            complete: function () {
                $('#loadingOverlay').fadeOut(); 
            }
        });
    });
    

    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
    
        $.get('/detail/' + id + '/edit', function (detail) {
            $('#idexpenses').val(detail.id);
            $('#travelrequestid').val(detail.travel_request_id);
            $('select[name="jenis_biaya"]').val(detail.jenis_perjalanan === 1 ? '1' : '0');
            $('input[name="deskripsi"]').val(detail.transportation); 
            $('textarea[name="keterangan"]').val(detail.description); 
            $('input[name="biaya"]').val(detail.cost); 
            $('input[name="qty"]').val(detail.quantity);
            $('input[name="man"]').val(detail.man);
            $('input[name="total"]').val(detail.total); 
    
            $('#biayaModal').modal('show');
        });
    });
    
    var urlSegments = window.location.pathname.split('/'); 
    var id = urlSegments[urlSegments.length - 1]; 

    $.ajax({
        url: '/statusapprove/' + id,  
        type: 'GET',
        success: function (response) {
            if (response.status_approve == 5 || response.status_approve == 1 || response.status_approve == 4 || response.status_approve == 6) {
                $('#addJabatan').addClass('hiddendetail')
            } else {
                $('#addJabatan').removeClass('hiddendetail');
            }
            updateButton(response.status_approve); 
        }
    });

    $(document).on('click', '.request-btn', function () {
        var idreques = $(this).attr('data-id');
        console.log(idreques);
        $('#requestId').val(idreques);
        $('#submitRequestModal').modal('show');
    });

    $('#confirmSubmit').click(function () {
        var idreques = $('#requestId').val();
        $('#submitRequestModal').modal('hide');
        $('#loadingOverlay').fadeIn();
        $.ajax({
            url: '/submitrequest/' + id,
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
                
                table.ajax.reload();
                updateButton(response.status_approve);
                console.log(response.status_approve)
                if (response.status_approve == 5 || response.status_approve == 1) {
                    $('#addJabatan').addClass('hiddendetail')
                } else {
                    $('#addJabatan').removeClass('hiddendetail');
                }
            },
            complete: function () {
                $('#loadingOverlay').fadeOut(); 
            }
        });
    })

    function updateButton(status) {
        var button = $('#requestButton');
        console.log(status)
        console.log('uptdae button')
        if (status == 0 || status == 2) {
            console.log('uptdae button submit')
            button.text('Submit Request')
                .removeClass('btn-danger')
                .addClass('btn-success')
                .attr('data-id', '5');
        } else if (status == 5) {
            console.log('uptdae button cencel')
            button.text('Cancel Request')
                .removeClass('btn-success')
                .addClass('btn-danger')
                .attr('data-id', '0');
        } else {
            button.addClass('hiddenbutton');
        }
    }

    $(document).on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        $('#deletedetail').val(id);
        $('#biayadeleteModal').modal('show');
    });

    $('#confirmDelete').click(function () {
        var id = $('#deletedetail').val();
        $('#biayadeleteModal').modal('hide');
        $('#loadingOverlay').fadeIn();
        $.ajax({
            url: '/detail/' + id,
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
                table.ajax.reload();
            }
            ,
            complete: function () {
                $('#loadingOverlay').fadeOut(); // Sembunyikan loading setelah request selesai
            }
        });
    });

});