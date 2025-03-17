$(document).ready(function() {
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        fixedHeader: true,
        ajax: routes.karyawansData,
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
            { data: 'name', name: 'user.name' }, 
            { data: 'email', name: 'user.email' },
            { data: 'departement', name: 'departement', className: 'text-nowrap'},
            { data: 'jabatan', name: 'jabatan', className: 'text-nowrap'},
            { data: 'status_user', name: 'status_user', className: 'text-nowrap' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
        ]
    });

    $('#addKarywans').click(function() {
        $('#karwanid').val('');
        $('#user_id').val('');
        $('#jabatan_id').val('');
        $('#departement_id').val('');
        $('#status_user').val('');
        $('#nomortlp').val('');
        $('#nik').val('');
    });

    $('#saveUser').click(function() {
        var id = $('#karwanid').val();
        var url = id ? '/karyawans/' + id : '/karyawans';
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: id ? 'PUT' : 'POST',
            user_id: $('#user_id').val(),
            jabatan_id: $('#jabatan_id').val(),
            departement_id: $('#departement_id').val(),
            status_user: $('#status_user').val(),
            nomortlp: $('#nomortlp').val(),
            nik: $('#nik').val(),
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

        $.get('/karyawans/' + id + '/edit', function(user) {
            if (!user || user.error) {
                alert('Data tidak ditemukan');
                return;
            }

            $('#karwanid').val(user.id);
            $('#user_id').val(user.user_id).trigger('change');
            $('#nomortlp').val(user.nomortlp).trigger('nomortlp');
            $('#nik').val(user.nik).trigger('nik');
            $('#departement_id').val(user.departement_id).trigger('change');
            $('#jabatan_id').val(user.jabatan_id).trigger('change');
            $('#status_user').val(user.status_user ?? "").trigger('change');


            // Tambahkan opsi jika tidak ada di dropdown
            addOptionIfNotExists('#user_id', user.user_id, user.name);
            addOptionIfNotExists('#departement_id', user.departement_id, user.departement_name);
            addOptionIfNotExists('#jabatan_id', user.jabatan_id, user.jabatan_name);

            $('#userModal').modal('show');
        });
    });

    // Fungsi untuk menambahkan opsi jika belum ada
    function addOptionIfNotExists(selector, value, text) {
        if ($(selector + " option[value='" + value + "']").length === 0) {
            $(selector).append(new Option(text, value));
        }
    }



    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deletekarwanid').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        var id = $('#deletekarwanid').val();
        $.ajax({
            url: '/karyawans/' + id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
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