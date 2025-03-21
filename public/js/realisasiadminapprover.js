$(document).ready(function () {
    // Ambil ID dari URL
    var urlSegments = window.location.pathname.split('/'); 
    var id = urlSegments[urlSegments.length - 1]; 

    var table = $('#tablePerjalanan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/approver/relaisasi/' + id + '/data',
            dataSrc: function(json) {
                console.log(json.status_approve_realisasi);
                $('#totalSebelum').text(formatRupiah(json.totalKeseluruhan));
                $('#totalSesudah').text(formatRupiah(json.totalSesudah));
                if (json.status_approve_realisasi == 4) {
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

    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
    
        $.get('/approve/statusapprove/' + id + '/detail', function (detail) {
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

    
    $.ajax({
        url: '/approve/statusapprove/' + id,  
        type: 'GET',
        success: function (response) {
            if (response.status_approve_realisasi == 4 || response.userApproval == 'Approve') {
                $('#reject').addClass('hiddenbutton')
                $('#approve').addClass('hiddenbutton')
                $('#hold').addClass('hiddenbutton')
            } else {
                $('#addJabatan').removeClass('hiddenbutton');
            }
            updateButton(response.status_approve_realisasi); 
        }
    });

    $(".action-button").click(function() {
        let id = $(this).data("id");
        let status = $(this).data("status"); 
        let komentar = status === 3 ? $("#komentarText").val() : null;
    
        console.log("ID yang diambil:", id, "Status:", status);
    
        let url = '/approver/realisasi/' + id + '/update'; 
        let data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'PUT',
            status_approve_realisasi: status,
            comentar: komentar
        };
    
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.berhasil) {
                    toastr.success(response.berhasil, "Sukses", { "closeButton": true, "progressBar": true });
    
                    setTimeout(function() {
                        window.location.href = '/approver';
                    }, 1000);
                }
                if (response.gagal) {
                    toastr.error(response.gagal, "Error", { "closeButton": true, "progressBar": true });
                }
                $('.modal').modal('hide');
                table.ajax.reload();
            }
        });
    });



});