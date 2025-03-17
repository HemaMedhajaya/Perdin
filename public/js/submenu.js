$(document).ready(function() {
    var table = $('#submenuTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: routes.submenusData,
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
            { data: 'route', name: 'route' },
            { data: 'menu_name', name: 'menu.name' },
            { data: 'type_label', name: 'type' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
    });

    $('#addSubmenu').click(function() {
        $('#menusid').val('');
        $('#name').val('');
        $('#route').val('');
        $('#type').val('');
        $('#menu_id').val('');

        function loadMenus() {
            $.get(routes.menusData, function(data) {
                var options = '<option value="">Pilih Menu Induk</option>';
                data.forEach(function(menu) {
                    options += '<option value="' + menu.id + '">' + menu.name + '</option>';
                });
                $('#menu_id').html(options); 
            });
        }
    
        loadMenus();
    });

    $('#saveUser').click(function() {
        var id = $('#submenusid').val();
        var url = id ? '/submenus/' + id : '/submenus';
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: id ? 'PUT' : 'POST',
            name: $('#name').val(),
            type: $('#type').val(),
            route: $('#route').val(),
            menu_id: $('#menu_id').val(),
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
    
        $.get('/submenus/' + id + '/edit', function(response) {
            var submenu = response.submenu; 
            var menus = response.menus;   

            $('#submenusid').val(submenu.id);
            $('#name').val(submenu.name);
            $('#route').val(submenu.route);
            $('#type').val(submenu.type);
    
            var options = '<option value="">Pilih Menu Induk</option>';
            menus.forEach(function(menu) {
                var selected = (menu.id == submenu.menu_id) ? 'selected' : '';
                options += '<option value="' + menu.id + '" ' + selected + '>' + menu.name + '</option>';
            });
            $('#menu_id').html(options); 
    
            $('#userModal').modal('show');
        });
    });

    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deletesubmenusid').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        var id = $('#deletesubmenusid').val();
        $.ajax({
            url: '/submenus/' + id,
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