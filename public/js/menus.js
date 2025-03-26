$(document).ready(function() {
    var table = $('#menusTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: routes.menusData,
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
        $('#menusid').val('');
        $('#name').val('');
        $('#route').val('');
        $('#icon').val('');
        $('#type').val('');
        $('#is_parent').val('');
        loadUsers();
    });

    function loadUsers() {
        $.get(routes.SuperadminRole, function(roles) { 
            $('#type').empty();
            $('#type').append('<option value="">Pilih Type</option>');
    
            roles.forEach(function(role) {
                $('#type').append(`<option value="${role.id}">${role.name}</option>`);
            });
        }).fail(function() {
            alert('Gagal mengambil data role. Pastikan API berjalan dengan benar.');
        });
    }
    

    $('#saveUser').click(function() {
        var id = $('#menusid').val();
        var url = id ? '/menus/' + id : '/menus';
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: id ? 'PUT' : 'POST',
            name: $('#name').val(),
            type: $('#type').val(),
            route: $('#route').val(),
            icon: $('#icon').val(),
            is_parent: $('#is_parent').val(),
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
        $.get('/menus/' + id + '/edit', function(menu) {
            $('#menusid').val(menu.id);
            $('#name').val(menu.name);
            $('#route').val(menu.route);
            $('#icon').val(menu.icon);
            $('#type').val(menu.type);
            $('#is_parent').val(menu.is_parent);
            $('#userModal').modal('show');
        });
    });

    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deletemenusid').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        var id = $('#deletemenusid').val();
        $.ajax({
            url: '/menus/' + id,
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