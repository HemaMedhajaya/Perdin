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
        $('#is_parent').val('');
        $('#submenusid').val('');
        $('#icon').val('');
        loadUsers();
    
        // Pastikan elemen tampil sesuai kondisi awal
        $('#menu_id').closest('.mb-2').hide();
        $('#icon').closest('.mb-2').hide(); 
    
    });

    function loadUsers(selectedType = '') {
        $.get(routes.SuperadminRole, function(roles) { 
            $('#type').empty();
            $('#type').append('<option value="">Pilih Type</option>');
    
            roles.forEach(function(role) {
                var selected = role.id == selectedType ? 'selected' : ''; // Cek apakah harus dipilih
                $('#type').append(`<option value="${role.id}" ${selected}>${role.name}</option>`);
            });
        }).fail(function() {
            alert('Gagal mengambil data role. Pastikan API berjalan dengan benar.');
        });
    }
    

    function loadMenus() {
        $.get(routes.menusData, function(data) {
            var options = '<option value="">Pilih Menu Induk</option>';
            data.forEach(function(menu) {
                options += '<option value="' + menu.id + '">' + menu.name + '</option>';
            });
            $('#menu_id').html(options); 
        });
    }

    $('#is_parent').change(function() {
        var selectedValue = $(this).val();

        if (selectedValue == "1") { 
            loadMenus();
            $('#menu_id').closest('.mb-2').show(); 
            $('#icon').closest('.mb-2').hide(); 
            $('#icon').html('');
        } else { 
            $('#menu_id').closest('.mb-2').hide(); 
            $('#icon').closest('.mb-2').show(); 
            $('#menu_id').html('');
        }
    });

    $('#saveUser').click(function() {
        var id = $('#submenusid').val();
        console.log(id);
        var url = id ? '/submenus/' + id : '/submenus';
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: id ? 'PUT' : 'POST',
            name: $('#name').val(),
            type: $('#type').val(),
            route: $('#route').val(),
            menu_id: $('#menu_id').val(),
            type_menu: $('#is_parent').val(),
            icon: $('#icon').val(),
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
            $('#is_parent').val(submenu.type_menu);
            $('#icon').val(submenu.icon);
            loadUsers(submenu.type);
    
            var options = '<option value="">Pilih Menu Induk</option>';
            menus.forEach(function(menu) {
                var selected = (menu.id == submenu.menu_id) ? 'selected' : '';
                options += '<option value="' + menu.id + '" ' + selected + '>' + menu.name + '</option>';
            });
            $('#menu_id').html(options);
    
            // Cek nilai type_menu (is_parent)
            if (submenu.type_menu == 1) {
                $('#menu_id').parent().show();  // Tampilkan dropdown menu induk
                $('#icon').parent().hide();  // Tampilkan dropdown menu induk
            } else {
                $('#menu_id').parent().hide();  // Sembunyikan dropdown menu induk
                $('#icon').parent().show();  // Sembunyikan dropdown menu induk

            }
    
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