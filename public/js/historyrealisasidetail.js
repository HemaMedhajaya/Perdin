$(document).ready(function () {
    // Ambil ID dari URL
    var urlSegments = window.location.pathname.split('/'); 
    var id = urlSegments[urlSegments.length - 1]; 

    var table = $('#tablePerjalanan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/historyrealisasi/realisasi/data/' + id,
            dataSrc: function(json) {
                $('#totalSesudah').text(formatRupiah(json.totalSesudah)); 
                return json.data;
            }
        },
        columns: [
            { 
                data: null, 
                orderable: false, 
                searchable: false, 
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + 1; 
                }
            },
            { data: 'jenis_perjalanan_sesudah', name: 'jenis_perjalanan_sesudah' },
            { data: 'description_sesudah', name: 'description_sesudah' },
            { 
                data: 'total_sesudah', 
                name: 'total_sesudah', 
                className: 'text-right',
                render: function(data) {
                    return data ? formatRupiah(data) : '-';
                }
            },
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
    
});