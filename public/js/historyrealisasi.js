$(document).ready(function () {
    var table = $('#perdiTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: routes.historyrealisasiData,
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
            { data: 'keperluan', name: 'keperluan' },
            { data: 'lokasikerja', name: 'lokasikerja' },
            { 
                data: 'status_and_action', 
                name: 'status_and_action', 
                orderable: false, 
                searchable: false, 
                className: 'text-center'
            },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        columnDefs: [
            { width: "5%", targets: 0 }, 
            { width: "30%", targets: 1 },
            { width: "30%", targets: 2 },
            { width: "30%", targets: 3 },
            { width: "15%", targets: 4 },  
            { width: "10%", targets: 5 }  
        ]
    });
    
});