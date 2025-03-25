$(document).ready(function () {
    var table = $('#perdiTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: routes.perdinData,
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
            { data: 'name_project', name: 'name_project' },
            { 
                data: 'status_and_action', 
                name: 'status_and_action', 
                orderable: false, 
                searchable: false, 
                className: 'text-center'
            },
            { 
                data: 'status_and_action_realisasi', 
                name: 'status_and_action_realisasi', 
                orderable: false, 
                searchable: false, 
                className: 'text-center'
            },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        columnDefs: [
            { width: "5%", targets: 0 }, 
            { width: "40%", targets: 1 },
            { width: "10%", targets: 2 },
            { width: "10%", targets: 3 },
            { width: "20%", targets: 4 }  
        ]
    });

    $(document).on('click', '#komentarreject', function () {
        var id = $(this).data('id'); 
        $.ajax({
            url: '/komentar/' + id, 
            type: 'GET', 
            success: function (response) {
                if (response.komentar) {
                    $('#komentarText').val(response.komentar);
                    $('#komentarshow').modal('show');
                } else {
                    console.error('Komentar tidak ditemukan');
                }
            },
            error: function (xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
            }
        });
    });
    
    

    $('#addPerdin').click(function () {
        $('#perdinid').val('');
        $('#name').val('');
        $('#nomortlp').val('');
        $('#departement_id').val('');
        $('#jabatan_id').val('');
        $('#projectname').val('');
        $('#lokasikerja').val('');
        $('#keperluan').val('');
        $('#nomorso').val('');

        $.get(routes.perdinDataUser, function (data) {
            if (data.name) {
                $('#name').val(data.name);
                $('#nomortlp').val(data.nomortlp);
                $('#departement_id').val(data.departement);
                $('#jabatan_id').val(data.jabatan);
            } else {
                alert('User tidak ditemukan');
            }
        }).fail(function () {
            alert("Gagal mengambil data user.");
        });
        $.get(routes.perdinCategory, function (data) {
            if (data.length > 0) {
                let checkboxes = "";
                data.forEach(function (item) {
                    checkboxes += `<label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="idproductcg" name="idproductcg[]" value="${item.id}"> 
                    ${item.name}
                </label><br>`;
                });
                $("#category-list").html(checkboxes);
            } else {
                $("#category-list").html("<p>Tidak ada kategori tersedia</p>");
            }
        }).fail(function () {
            alert("Gagal mengambil data kategori.");
        });
        $.get(routes.perdinUserPJ, function (data) {
            let options = "";
            data.forEach(user => {
                options += `<option value="${user.id}">${user.name}</option>`;
            });
        
            $("#user_id, #userpj_id").html(options);
        
            $("#user_id, #userpj_id").select2({
                placeholder: "Pilih User",
                allowClear: true,
                width: "100%", 
                dropdownParent: $("#user_id").parent(),
            });
            
        });
        

    });

    $('#saveUser').click(function () {
        var id = $('#perdinid').val();
        var url = id ? '/perdin/' + id : '/perdin';
        var selectedCategories = [];
        $('input[name="idproductcg[]"]:checked').each(function () {
            selectedCategories.push($(this)
                .val());
        });
        var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: id ? 'PUT' : 'POST',
            name: $('#name').val(),
            user_id: $('#user_id').val(),
            userpj_id: $('#userpj_id').val(),
            name_project: $('#projectname').val(),
            nomorso: $('#nomorso').val(),
            lokasikerja: $('#lokasikerja').val(),
            keperluan: $('#keperluan').val(),
            idproductcg: selectedCategories,
            // transportasi: {
            //     deskripsi: $('input[name="transportasi[deskripsi][]"]').map(function () {
            //         return $(this).val();
            //     }).get(),
            //     biaya: $('input[name="transportasi[biaya][]"]').map(function () {
            //         return $(this).val();
            //     }).get(),
            //     qty: $('input[name="transportasi[qty][]"]').map(function () {
            //         return $(this).val();
            //     }).get(),
            //     total: $('input[name="transportasi[total][]"]').map(function () {
            //         return $(this).val();
            //     }).get(),
            //     keterangan: $('textarea[name="transportasi[keterangan][]"]').map(function () {
            //         return $(this).val();
            //     }).get(),
            // },
        };

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.berhasil) {
                    toastr.success(response.berhasil, "Sukses", {
                        "closeButton": true,
                        "progressBar": true
                    });
                }
                if (response.gagal) {
                    toastr.error(response.gagal, "Error", {
                        "closeButton": true,
                        "progressBar": true
                    });
                }
                $('#userModal').modal('hide');
                table.ajax.reload();
            },
            error: function (xhr) {
                toastr.error("Terjadi kesalahan!", "Error", {
                    "closeButton": true,
                    "progressBar": true
                });
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');

        $.get('/perdin/' + id + '/edit', function (perdin) {
            $('#perdinid').val(perdin.id);
            $('#name').val(perdin.user.name);
            $('#departement_id').val(perdin.karyawan.departement.name);
            $('#jabatan_id').val(perdin.karyawan.jabatan.name);
            $('#nomortlp').val(perdin.karyawan.nomortlp);
            $('#projectname').val(perdin.name_project);
            $('#nomorso').val(perdin.nomorso);
            $('#lokasikerja').val(perdin.lokasikerja);
            $('#keperluan').val(perdin.keperluan);
            $('#userModal').modal('show');

            // Panggil kategori setelah mendapatkan data utama
            $.get(routes.perdinCategory, function (allCategories) {
                let selectedCategories = perdin.categorypf.map(item => item.category.id);
                let checkboxes = "";

                allCategories.forEach(function (category) {
                    let isChecked = selectedCategories.includes(category.id) ? "checked" : "";
                    checkboxes += `<label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="idproductcg[]" value="${category.id}" ${isChecked}> 
                        ${category.name}
                    </label><br>`;
                });

                $("#category-list").html(checkboxes);
            });

            $.get(routes.perdinUserPJ, function (data) {
                let options = "";
                let selectedUsers = perdin.participants.map(p => p.user_id);

                // Ambil user_id dari penanggungjawab (dari elemen pertama dalam array)
                let selectedUserPJ = (perdin.penanggungjawab && perdin.penanggungjawab.length > 0) ? perdin.penanggungjawab[0].user_id : null;

                // Loop untuk menambahkan semua opsi user
                data.forEach(user => {
                    let isSelected = selectedUsers.includes(user.id) ? "selected" : "";
                    options += `<option value="${user.id}" ${isSelected}>${user.name}</option>`;
                });

                // Set options ke dalam elemen select
                $("#user_id, #userpj_id").html(options);

                // Jika selectedUserPJ ada, set sebagai nilai default di #userpj_id
                if (selectedUserPJ) {
                    $("#userpj_id").val(selectedUserPJ).trigger('change');
                } else {
                    // Jika penanggungjawab kosong, biarkan #userpj_id tidak terpilih
                    $("#userpj_id").val(null).trigger('change');
                }

                // Inisialisasi Select2
                $("#user_id, #userpj_id").select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    width: "100%",
                    dropdownParent: $("#user_id").parent()
                });

            });

        });
    });


    $(document).on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        $('#deleteperdinid').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function () {
        var id = $('#deleteperdinid').val();
        $.ajax({
            url: '/perdin/' + id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.berhasil) {
                    toastr.success(response.berhasil, "Sukses", {
                        "closeButton": true,
                        "progressBar": true
                    });
                }
                if (response.gagal) {
                    toastr.error(response.gagal, "Error", {
                        "closeButton": true,
                        "progressBar": true
                    });
                }
                $('#deleteModal').modal('hide');
                table.ajax.reload();
            }
        });
    });

    
    
});