$(document).ready(function () {
    // Ambil ID dari URL
    var urlSegments = window.location.pathname.split('/'); 
    var id = urlSegments[urlSegments.length - 1]; 

    var table = $('#detailTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/approver/detail/' + id + '/data',
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

    $(document).on('click', '.detail-btn', function () {
        var id = $(this).data('id');
    
        $.get('/approver/detail/' + id + '/edit', function (detail) {
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

    $(".action-button").click(function() {
        let id = $(this).data("id");
        let status = $(this).data("status"); 
        let komentar = status === 2 ? $("#komentarText").val() : null;
    
        console.log("ID yang diambil:", id, "Status:", status);
    
        let url = '/approver/detail/' + id + '/update'; 
        let data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'PUT',
            status_approve: status,
            comentar: komentar
        };
        $('.modal').modal('hide');
        $('#loadingOverlay').fadeIn();
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
                
                table.ajax.reload();
            },
            complete: function () {
                $('#loadingOverlay').fadeOut(); 
            }
        });
    });


    var idtravelrequest = $('#idtravelrequest').val();
    console.log(idtravelrequest);
    $.ajax({
        url: '/statusapproveadmin/' + idtravelrequest,  
        type: 'GET',
        success: function (response) {
            console.log('sampai');
            if (response.status_approve == 1 || response.status_approve == 2 || response.userApproval == 'Approve') {
                $('#reject').addClass('hiddenbutton')
                $('#approve').addClass('hiddenbutton')
            } else {
                $('#addJabatan').removeClass('hiddendetail');
            }
        }
    });

    
});