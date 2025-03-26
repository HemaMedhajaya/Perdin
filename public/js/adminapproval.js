$(document).ready(function() {
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: routes.adminapprovalData,
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

    $('#addJabatan').click(function() {
        $('#adminapproveid').val('');
        $('#name').val('');
    });

    $('#saveUser').click(function() {
        var id = $('#adminapproveid').val();
        var url = '/adminapproval/update/' + id;
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method:'PUT',
            name: $('#name').val(),
            udf1: $('#udf1').val(),
            udf2: $('#udf2').val(),
            udf3: $('#udf3').val(),
            udf4: $('#udf4').val(),
            udf5: $('#udf5').val(),
            udf6: $('#udf6').val(),
            udf7: $('#udf7').val(),
            udf8: $('#udf8').val(),
            udf9: $('#udf9').val(),
            udf10: $('#udf10').val(),
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

    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
    
        $.get('/adminapproval/edit/' + id, function (response) {
            if (response.adminapproval) {
                $('#adminapproveid').val(response.adminapproval.id);
                $('#name').val(response.adminapproval.name);
    
                var users = response.user;
    
                var selectedUserIds = [
                    response.adminapproval.udf1,
                    response.adminapproval.udf2,
                    response.adminapproval.udf3,
                    response.adminapproval.udf4,
                    response.adminapproval.udf5,
                    response.adminapproval.udf6,
                    response.adminapproval.udf7,
                    response.adminapproval.udf8,
                    response.adminapproval.udf9,
                    response.adminapproval.udf10
                ];
    
                for (var i = 1; i <= 10; i++) {
                    var selectId = '#udf' + i;
                    $(selectId).empty(); 

                    $(selectId).append('<option value="">Pilih Admin</option>');    
    
                    
                    users.forEach(user => {
                        var isSelected = (user.id == selectedUserIds[i - 1]) ? 'selected' : '';
                        $(selectId).append(`<option value="${user.id}" ${isSelected}>${user.name}</option>`);
                    });
                }
    
                $('#userModal').modal('show');
            }
        });
    });
    
    

    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deleteadminapproveid').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        var id = $('#deleteadminapproveid').val();
        $.ajax({
            url: '/jabatan/' + id,
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