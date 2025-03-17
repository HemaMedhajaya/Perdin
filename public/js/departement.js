$(document).ready(function() {
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: routes.depatementData,
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
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        columnDefs: [
            {width: "5%", targets: 0},
            {width: "70%", targets: 1},
            {width: "25", targets: 2}
        ]
    });

    $('#addDepartement').click(function() {
        $('#departementid').val('');
        $('#name').val('');
    });

    $('#saveUser').click(function() {
        var id = $('#departementid').val();
        var url = id ? '/departement/' + id : '/departement';
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: id ? 'PUT' : 'POST',
            name: $('#name').val(),
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
        $.get('/departement/' + id + '/edit', function(user) {
            $('#departementid').val(user.id);
            $('#name').val(user.name);
            $('#userModal').modal('show');
        });
    });

    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deletedepartementid').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        var id = $('#deletedepartementid').val();
        $.ajax({
            url: '/departement/' + id,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content'), },
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