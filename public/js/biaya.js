$(document).ready(function() {
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: routes.baiayaData,
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
            { data: 'name', name: 'name' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        columnDefs: [
            {width: "5%", targets: 0},
            {width: "60%", targets: 1},
            {width: "20%", targets: 2},
            {width: "15", targets: 3}
        ]
    });

    $('#addBiaya').click(function() {
        $('#biayaId').val('');
        $('#name').val('');
    });

    $('#saveUser').click(function() {
        var id = $('#biayaId').val();
        var url = id ? '/biaya/' + id : '/biaya';
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: id ? 'PUT' : 'POST',
            name: $('#name').val(),
            status: $('#status').val(),
        };

        $.ajax({
            url: url,
            type: 'POST',  
            data: data,
            success: function(response) {
                if (response.berhasil) {
                    toastr.success(response.berhasil, "Sukses", { "closeButton": true, "progressBar": true });
                }
                if (response.gagal) {
                    toastr.error(response.gagal, "Error", { "closeButton": true, "progressBar": true });
                }
                $('#userModal').modal('hide');
                table.ajax.reload();
            },
            error: function(xhr) {
                toastr.error("Terjadi kesalahan!", "Error", { "closeButton": true, "progressBar": true });
            }
        });
    });

    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get('/biaya/' + id + '/edit', function(biaya) {
            $('#biayaId').val(biaya.id);
            $('#name').val(biaya.name);
            $('#status').val(biaya.status);
            $('#userModal').modal('show');
        });
    });

    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deletebiayaId').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        var id = $('#deletebiayaId').val();
        $.ajax({
            url: '/biaya/' + id,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                if (response.berhasil) {
                    toastr.success(response.berhasil, "Sukses", { "closeButton": true, "progressBar": true });
                }
                if (response.gagal) {
                    toastr.error(response.gagal, "Error", { "closeButton": true, "progressBar": true });
                }
                $('#deleteModal').modal('hide');
                table.ajax.reload();
            }
        });
    });
});