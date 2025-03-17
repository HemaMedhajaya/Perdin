$(document).ready(function() {
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: routes.userData,
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
            { data: 'email', name: 'email' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ]
    });

    $('#addUser').click(function() {
        $('#userId').val('');
        $('#name').val('');
        $('#email').val('');
    });

    $('#saveUser').click(function() {
        var id = $('#userId').val();
        var url = id ? '/users/' + id : '/users';
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: id ? 'PUT' : 'POST',
            name: $('#name').val(),
            email: $('#email').val(),
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
        $.get('/users/' + id + '/edit', function(user) {
            $('#userId').val(user.id);
            $('#name').val(user.name);
            $('#email').val(user.email);
            $('#userModal').modal('show');
        });
    });

    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deleteUserId').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        var id = $('#deleteUserId').val();
        $.ajax({
            url: '/users/' + id,
            type: 'DELETE',
            _token: $('meta[name="csrf-token"]').attr('content'),
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