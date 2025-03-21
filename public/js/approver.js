$(document).ready(function() {
    var table = $('#approverTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: routes.approverData,
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
            { data: 'user_name', name: 'user.name' },
            { data: 'name_project', name: 'name_project' },
            { data: 'status_approve', name: 'status_approve', orderable: false, searchable: false, className: 'text-center' },
            { data: 'status_approve_realisasi', name: 'status_approve_realisasi', orderable: false, searchable: false, className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        columnDefs: [
            {width: "5%", targets: 0},
            {width: "30%", targets: 1},
            {width: "40%", targets: 2},
            {width: "15%", targets: 3},
            {width: "5", targets: 4}
        ]
    });

    $('#addJabatan').click(function() {
        $('#jabatanid').val('');
        $('#name').val('');
    });

    $('#saveUser').click(function() {
        var id = $('#jabatanid').val();
        var url = id ? '/jabatan/' + id : '/jabatan';
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

    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');

        $.get('/approver/' + id + '/edit', function (approver) {
            $('#approverid').val(approver.id);
            $('#name').val(approver.user.name);
            $('#departement_id').val(approver.karyawan.departement.name);
            $('#jabatan_id').val(approver.karyawan.jabatan.name);
            $('#nomortlp').val(approver.karyawan.nomortlp);
            $('#projectname').val(approver.name_project);
            $('#nomorso').val(approver.nomorso);
            $('#lokasikerja').val(approver.lokasikerja);
            $('#keperluan').val(approver.keperluan);
            $('#tolakrequest').attr('data-id', approver.id);
            $('#userModal').modal('show');

            // Panggil kategori setelah mendapatkan data utama
            $.get(routes.approverCategory, function (allCategories) {
                let selectedCategories = approver.categorypf.map(item => item.category.id);
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

            $.get(routes.approverUserPJ, function (data) {
                let options = "";
                let selectedUsers = approver.participants.map(p => p.user_id);

                // Ambil user_id dari penanggungjawab (dari elemen pertama dalam array)
                let selectedUserPJ = (approver.penanggungjawab && approver.penanggungjawab.length > 0) ? approver.penanggungjawab[0].user_id : null;

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

                $("#user_id, #userpj_id").select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    width: "100%",
                    dropdownParent: $("#user_id").parent()
                });

                // Tambahkan transportasi & akomodasi
                let transportasiItems = "", akomodasiItems = "";
                approver.expenses.forEach(expense => {
                    if (expense.jenis_perjalanan == 1) {
                        transportasiItems += generateTransportasiItem(expense);
                    } else if (expense.jenis_perjalanan == 0) {
                        akomodasiItems += generateAkomodasiItem(expense);
                    }
                });

                $('#transportasi-container').html(transportasiItems);
                $('#akomodasi-container').html(akomodasiItems);
            });

        });
    });

    function generateTransportasiItem(expense) {
        return `<div class="transportasi-item row position-relative mb-3">
            <div class="col-md-6">
                <input type="text" name="transportasi[deskripsi][]" class="form-control mb-2" value="${expense.transportation}" placeholder="Deskripsi">
                <input type="number" name="transportasi[biaya][]" class="form-control mb-2" value="${expense.cost}" placeholder="Biaya">
                <input type="number" name="transportasi[qty][]" class="form-control mb-2" value="${expense.quantity}" placeholder="Qty">
            </div>
            <div class="col-md-6">
                <input type="number" name="transportasi[total][]" class="form-control mb-2" value="${expense.total}" placeholder="Total" readonly>
                <textarea name="transportasi[keterangan][]" class="form-control mb-2" placeholder="Keterangan">${expense.description}</textarea>
                <input type="hidden" name="transportasi[jenis_perjalanan][]" value="1">
            </div>
        </div>`;
    }

    function generateAkomodasiItem(expense) {
        return `<div class="akomodasi-item row position-relative mb-3">
            <div class="col-md-6">
                <input type="text" name="akomodasi[deskripsi][]" class="form-control mb-2" value="${expense.transportation}" placeholder="Deskripsi">
                <input type="number" name="akomodasi[biaya][]" class="form-control mb-2" value="${expense.cost}" placeholder="Biaya">
                <input type="number" name="akomodasi[qty][]" class="form-control mb-2" value="${expense.quantity}" placeholder="Qty">
            </div>
            <div class="col-md-6">
                <input type="number" name="akomodasi[total][]" class="form-control mb-2" value="${expense.total}" placeholder="Total" readonly>
                <textarea name="akomodasi[keterangan][]" class="form-control mb-2" placeholder="Keterangan">${expense.description}</textarea>
                <input type="hidden" name="akomodasi[jenis_perjalanan][]" value="0">
            </div>
        </div>`;
    }

    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deletejabatanid').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        var id = $('#deletejabatanid').val();
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
    $("#tolakrequest").click(function() {
        let id = $(this).data("id");
        console.log("ID yang diambil:", id);
        $("#travelrequestid").val(id);
    });

    $("#saveUser").click(function(){
        let alasan = $("#komentarText").val();
        let status = $("#tolaUser").attr("statusapprove");

        if(alasan.trim() === ""){
            alert("Alasan penolakan harus diisi!");
            return;
        }

        $.post("/path/to/reject", { statusapprove: status, komentar: alasan }, function(response){
            alert("User telah ditolak!");
            location.reload();
        });
    });
});