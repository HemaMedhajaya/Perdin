<?php

namespace App\Http\Controllers;
use App\Exports\KasbonExcel;
use App\Exports\KasbonExport;
use App\Mail\TravelRequestMail;
use App\Models\Categoryproduct;
use App\Models\MatrixApprovals;
use App\Models\PermissionRole;
use App\Models\Travelcategory;
use App\Models\TravelExpense;
use App\Models\TravelParticipant;
use App\Models\TravelPenanggungjawab;
use App\Models\TravelRealisasi;
use App\Models\User;
use App\Models\TravelRequest;
use App\Models\UserMatrixApprovals;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf;



use Illuminate\Http\Request;

class TravelRequestController extends Controller
{
    protected $name;

    public function __construct()
    {
        $email = session('email');
        if ($email) {
            $this->name = User::where('email', $email)->pluck('name')->first();
        }
    }
    public function index()
    {
        $data['permissionAddPerjalananDinas'] = PermissionRole::getPermission('Add Perjalanan Dinas', Auth::user()->role);
        return view('user.travel.index', ['name' => $this->name, 'data' => $data]);
    }

    public function getData(Request $request)
    {
        $permissionEditPerjalananDinas = PermissionRole::getPermission('Edit Perjalanan Dinas', Auth::user()->role);
        $permissionDeletePerjalananDinas = PermissionRole::getPermission('Delete Perjalanan Dinas', Auth::user()->role);

        $userid = session('user_id');
        if ($request->ajax()) {
            $data = TravelRequest::with(['user', 'participants', 'expenses'])
                ->select('id', 'name_project', 'status_approve','status_approve_realisasi') // Pastikan ambil status_approve
                ->where('user_id', $userid)
                ->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addColumn('approve_perdin', function ($data) {
                    $approval = UserMatrixApprovals::where('id_perdin', $data->id)
                        ->where('id_matrix', 1)
                        ->where('status', 'Approve')
                        ->orderBy('number', 'desc') 
                        ->first();
                
                    if ($approval) {
                        $isLastApproval = !UserMatrixApprovals::where('id_perdin', $data->id)
                            ->where('id_matrix', 1)
                            ->where('status', 'Approve')
                            ->where('number', '>', $approval->number)
                            ->exists();
                
                        if ($isLastApproval) {
                            $karyawan = Karyawan::where('user_id', $approval->id_user)->first();
                            return '<span>' . $karyawan->jabatan->name . ' <i class="fa fa-check-circle text-success"></i></span>';
                        }
                    }
                    return '-';
                })            
                ->addColumn('approve_realisasi', function ($data) {
                    $approval = UserMatrixApprovals::where('id_perdin', $data->id)
                        ->where('id_matrix', 2)
                        ->where('status', 'Approve')
                        ->orderBy('number', 'desc') 
                        ->first();
                
                    if ($approval) {
                        $isLastApproval = !UserMatrixApprovals::where('id_perdin', $data->id)
                            ->where('id_matrix', 2)
                            ->where('status', 'Approve')
                            ->where('number', '>', $approval->number) 
                            ->exists();
                
                        if ($isLastApproval) {
                            $karyawan = Karyawan::where('user_id', $approval->id_user)->first();
                            return $karyawan ? $karyawan->jabatan->name : 'Tidak ditemukan';
                        }
                    }
                    return '-';
                })
                ->addColumn('status_and_action', function ($data) {
                    $status = [
                        0 => '<span class="badge bg-label-secondary">Draft</span>',
                        5 => '<span class="badge bg-label-warning">Diproses</span>',
                        1 => '<span class="badge bg-label-success">Disetujui</span>',
                        2 => '<span class="badge bg-label-danger">Ditolak</span>',
                    ];
                    $statusHtml = $status[$data->status_approve] ?? '<span class="badge bg-label-success">Disetujui</span>';

                    if ($data->status_approve == 2) {
                        $actionreject = '
                            <button type="button" id="komentarreject" class="btn btn-sm btn-link" data-id="' . $data->id . '" data-toggle="tooltip" data-placement="top" title="Komentar">
                                <i class="bx bx-show"></i>
                            </button>
                        ';
                    } else {
                        $actionreject = '';
                    }
                    return  $statusHtml . ' ' . $actionreject;
                })
                ->addColumn('status_and_action_realisasi', function ($data) {
                    $status = [
                        2 => '<span class="badge bg-label-warning">Diproses</span>',
                        1 => '<span class="badge bg-label-secondary">Draft</span>',
                        3 => '<span class="badge bg-label-danger">Ditolak</span>',
                        4 => '<span class="badge bg-label-success">Disetujui</span>',
                    ];
                    $statusHtml = $status[$data->status_approve_realisasi] ?? '<span class="badge badge-secondary">-</span>';

                    $realisasiUrl = route('perdin.realisasi', ['id' => $data->id]);
                    $actionRealisasiHtml = '';

                    if (in_array($data->status_approve_realisasi, [1, 2, 3, 4])) {
                        $actionRealisasiHtml = '
                            <a href="' . $realisasiUrl . '" class="btn btn-sm btn-link" data-toggle="tooltip" data-placement="top" title="Realisasi">
                                <i class="bx bx-calendar-check"></i>
                            </a>
                        ';
                    }

                    if ($data->status_approve_realisasi == 3) {
                        $actionreject = '
                            <button type="button" id="komentarreject" class="btn btn-sm btn-link" data-id="' . $data->id . '" data-toggle="tooltip" data-placement="top" title="Komentar">
                                <i class="bx bx-show"></i>
                            </button>
                        ';
                    } else {
                        $actionreject = '';
                    }

                    return $statusHtml . ' ' . $actionRealisasiHtml . ' ' . $actionreject;

                })
                ->addColumn('action', function ($data) use ($permissionEditPerjalananDinas, $permissionDeletePerjalananDinas) {
                    $editButton = '';
                    $deleteButton = '';
                    
                    // Pengecekan izin Edit
                    if ($permissionEditPerjalananDinas > 0) {
                        $editButton = '
                            <button class="btn btn-sm btn-primary edit-btn" data-id="' . $data->id . '" data-toggle="tooltip" data-placement="top" title="Edit">
                                <i class="bx bx-edit"></i>
                            </button>
                        ';
                    }
                    
                    // Pengecekan izin Delete
                    if ($permissionDeletePerjalananDinas > 0) {
                        $deleteButton = '
                            <button class="btn btn-sm btn-danger delete-btn" data-id="' . $data->id . '" data-toggle="tooltip" data-placement="top" title="Hapus">
                                <i class="bx bx-trash"></i>
                            </button>
                        ';
                    }
            
                    // Menambahkan link Detail
                    $detailUrl = route('perdin.detail', ['id' => $data->id]);
                    $realisasiUrl = route('perdin.realisasi', ['id' => $data->id]);
            
                    // Mengembalikan tombol sesuai izin
                    return $editButton . '
                        <a href="' . $detailUrl . '" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Detail">
                            <i class="bx bx-detail"></i>
                        </a>
                        ' . $deleteButton . '
                    ';
                })
                ->rawColumns(['status_and_action', 'action','status_and_action_realisasi', 'approve_perdin']) 
                ->make(true);
        }
    }
    public function getUser()
    {
        $userId = session('user_id'); 

        if (!$userId) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $karyawan = Karyawan::with(['user', 'jabatan', 'departement'])->where('user_id', $userId)->first();

        if ($karyawan) {
            return response()->json([
                'name' => $karyawan->user ? $karyawan->user->name : null,
                'nomortlp' => $karyawan->nomortlp, 
                'jabatan' => $karyawan->jabatan ? $karyawan->jabatan->name : null, 
                'departement' => $karyawan->departement ? $karyawan->departement->name : null 
            ]);
        }

        return response()->json(['message' => 'User tidak ditemukan'], 404);
    }

    public function getCategoryProduct()
    {
        $categorypd = Categoryproduct::all();
        return response()->json($categorypd);
    }

    public function getUserpj()
    {
        $userId = session('user_id'); 
        $users = User::where('id', '!=', $userId)->get(); 
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required', 
            'idproductcg' => 'required', 
        ]);
        

        $travelRequest = TravelRequest::create([
            'user_id' => session('user_id'),
            'destination' => 'Jakarta',  
            'purpose' => 'Yogyakarta',
            'name_project' => $request->name_project,  
            'nomorso' => $request->nomorso,
            'lokasikerja' => $request->lokasikerja,
            'keperluan' => $request->keperluan
        ]);

        $travel_request_id = $travelRequest->id;
        if ($request->has('idproductcg') && is_array($request->idproductcg)) {
            foreach ($request->idproductcg as $categoryId) {
                TravelCategory::create([
                    'travel_request_id' => $travel_request_id,
                    'category_id' => $categoryId,
                ]);
            }
        } else {
            dd("Tidak ada kategori yang dipilih");
        }

        foreach ($request->user_id as $userId) {
            TravelParticipant::create([
                'travel_request_id' => $travel_request_id,
                'user_id' => $userId,
            ]);
        }

        foreach ($request->userpj_id as $userpjId) {
            TravelPenanggungjawab::create([
                'travel_request_id' => $travel_request_id,
                'user_id' => $userpjId,
            ]);
        }

        // Ambil data dari model MatrixApprovals untuk id 1 dan 2
        $matrixApprovals = MatrixApprovals::whereIn('id', [1, 2])->get(); // Bisa ditambah id lain jika perlu

        $totalCount = 0; // Untuk menghitung total data yang akan ditambahkan

        // Loop melalui setiap baris data di MatrixApprovals
        foreach ($matrixApprovals as $matrixApproval) {
            $udfValues = [];

            // Loop dari udf1 sampai udf10 (atau lebih, jika perlu)
            for ($i = 1; $i <= 10; $i++) { 
                $columnName = 'udf' . $i;

                // Pastikan kolom ada dan nilainya bukan 0 atau null
                if (!empty($matrixApproval->$columnName) && $matrixApproval->$columnName != 0) {
                    $udfValues[$columnName] = $matrixApproval->$columnName;
                }
            }

            // Hitung jumlah kolom udf yang memiliki nilai
            $udfCount = count($udfValues);
            $totalCount += $udfCount; // Tambahkan ke total count

            // Loop untuk mengisi number dan id_user secara berurutan
            $number = 1;
            foreach ($udfValues as $column => $id_user) {
                // Buat instance model UserMatrixApprovals baru
                $userMatrixApproval = new UserMatrixApprovals();

                // Isi data ke model
                $userMatrixApproval->id_perdin = $travel_request_id;
                $userMatrixApproval->id_matrix = $matrixApproval->id;
                $userMatrixApproval->number = $number;
                $userMatrixApproval->id_user = $id_user;
                $userMatrixApproval->status = 'Not Yet';

                // Simpan ke database
                $userMatrixApproval->save();

                $number++;
            }
        }



        return response()->json(['berhasil' => 'Perjalanan dinas berhasil ditambahkan!']);

    }

    public function edit($id)
    {
        $travelrequest = TravelRequest::with(['user', 'participants', 'penanggungjawab', 'expenses', 'categorypf.category','karyawan', 'karyawan.jabatan','karyawan.departement'])->find($id);

        if (!$travelrequest) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($travelrequest);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required', 
            'idproductcg' => 'required', 
            'nomorso' => 'required',
        ]);

        $travelRequest = TravelRequest::findOrFail($id);
        
        // Update Travel Request
        $travelRequest->update([
            'user_id' => session('user_id'),
            'destination' => 'Jakarta',
            'purpose' => 'Yogyakarta',
            'name_project' => $request->name_project,
            'nomorso' => $request->nomorso,
            'lokasikerja' => $request->lokasikerja,
            'keperluan' => $request->keperluan
        ]);

        // **Update Travel Categories**
        TravelCategory::where('travel_request_id', $id)->delete();
        if ($request->has('idproductcg') && is_array($request->idproductcg)) {
            foreach ($request->idproductcg as $categoryId) {
                TravelCategory::create([
                    'travel_request_id' => $id,
                    'category_id' => $categoryId,
                ]);
            }
        }

        // **Update Travel Participants**
        TravelParticipant::where('travel_request_id', $id)->delete();
        foreach ($request->user_id as $userId) {
            TravelParticipant::create([
                'travel_request_id' => $id,
                'user_id' => $userId,
            ]);
        }

        TravelPenanggungjawab::where('travel_request_id', $id)->delete();
        foreach ($request->userpj_id as $userpjId) {
            TravelPenanggungjawab::create([
                'travel_request_id' => $id,
                'user_id' => $userpjId,
            ]);
        }

        return response()->json(['berhasil' => 'Perjalanan dinas berhasil diperbarui']);

    }

    public function destroy($id)
    {
        // Cari TravelRequest berdasarkan ID
        $travelRequest = TravelRequest::findOrFail($id);

        // Hapus semua relasi terlebih dahulu
        TravelCategory::where('travel_request_id', $id)->delete();
        TravelParticipant::where('travel_request_id', $id)->delete();
        TravelExpense::where('travel_request_id', $id)->delete();

        // Hapus data utama
        $travelRequest->delete();

        return response()->json(['berhasil' => 'Perjalanan dinas berhaisl dihapus']);
    }

    public function detail($id)
    {
        $travelRequest = TravelRequest::find($id);
        $nameProject = $travelRequest ? $travelRequest->name_project : 'Tidak Diketahui';

        $penanggungJawab = TravelPenanggungjawab::where('travel_request_id', $id)->with('user')->first();
        $nameUser = $penanggungJawab && $penanggungJawab->user ? $penanggungJawab->user->name : 'Tidak Diketahui';
        return view('user.travel.detail', [
            'name' => $this->name,
            'nameprojec' => $nameProject,
            'nameuser' => $nameUser,
            'id' => $id,
            'status_approver' => $travelRequest->status_approve
        ]);
    }

    public function getDataDetail($id)
    {
        $data = TravelExpense::with('travelRequest')->where('travel_request_id', $id)->get();

        $total = $data->sum('total');

        return DataTables::of($data)
            ->addColumn('jenis_perjalanan', function ($data) {
                return $data->jenis_perjalanan == 1 ? 'Transportasi' : 'Akomodasi';
            })
            ->addColumn('description', function ($data) {
                return $data->description ? $data->description : '-';
            })
            ->addColumn('total', function ($data) {
                return $data->total ? $data->total : '-';
            })
            ->addColumn('status_approve', function ($data) {
                // Akses status_approve dari travelRequest
                $status_approve = $data->travelRequest->status_approve;

                $status = [
                    0 => '<span class="badge bg-label-secondary">Draft</span>',
                    5 => '<span class="badge bg-label-warning">Diproses</span>',
                    1 => '<span class="badge bg-label-success">Disetujui</span>',
                    2 => '<span class="badge bg-label-danger">Ditolak</span>',
                ];
                return $status[$status_approve] ?? '<span class="badge bg-label-success">Disetujui</span>';
            })
            ->addColumn('action', function ($data) {
                if ($data->travelRequest->status_approve == 5) {
                    return '';
                }
                return '
                    <button class="btn btn-sm btn-primary edit-btn" data-id="' . $data->id . '" data-toggle="tooltip" data-placement="top" title="Edit">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $data->id . '" data-toggle="tooltip" data-placement="top" title="Hapus">
                        <i class="bx bx-trash"></i>
                    </button>
                ';
            })
            ->with('totalKeseluruhan', $total)
            ->with('status_approve', $data->isEmpty() ? 0 : $data->first()->travelRequest->status_approve) // Kirim status_approve ke frontend
            ->rawColumns(['status_approve', 'action']) // Kolom status_approve dan action menggunakan HTML
            ->make(true);
    }

    public function saveBiaya(Request $request, $id = null)
    {
        // Validasi data yang dikirim
        if (!$request->has('jenis_biaya') || !$request->has('deskripsi') || !$request->has('biaya') || !$request->has('qty') || !$request->has('total')) {
            return response()->json(['gagal' => 'Data tidak lengkap!'], 400);
        }

        // Tentukan travel request ID
        $travelRequestId = $request->travel_request_id;
        

        // Data yang akan disimpan/diupdate
        $data = [
            'travel_request_id' => $travelRequestId,
            'transportation' => $request->deskripsi,
            'cost' => $request->biaya,
            'quantity' => $request->qty,
            'man' => $request->man,
            'total' => $request->total,
            'description' => $request->keterangan,
            'jenis_perjalanan' => $request->jenis_biaya, // 1 untuk transportasi, 0 untuk akomodasi
        ];

        if ($id) {
            $biaya = TravelExpense::find($id);
            $relasi = TravelRealisasi::where('idexpenses',$id)->first();
            if (!$biaya) {
                return response()->json(['gagal' => 'Data tidak ditemukan!'], 404);
            }
            $biaya->update($data);
            $relasi->update($data);
            return response()->json(['berhasil' => 'Biaya perjalanan dinas berhasil diperbarui!']);
        }

        $biaya = TravelExpense::create($data);
        $expenseId = $biaya->id;
        if ($expenseId) {
            $dataRealisasi = array_merge($data, ['idexpenses' => $expenseId]);
            TravelRealisasi::create($dataRealisasi);
        } else {
            return response()->json(['gagal' => 'Id gak ada']);
        }

        return response()->json(['berhasil' => 'Biaya perjalanan dinas berhasil ditambahkan!']);
    }

    public function editdetail($id)
    {
        $detail = TravelExpense::find($id);
        return response()->json($detail);
    }

    public function destroydetail($id)
    {
        $travelExpnse = TravelExpense::findOrFail($id);
        $travelExpnse->delete();
        $relasi = TravelRealisasi::where('idexpenses',$id)->first();
        $relasi->delete();
        return response()->json(['berhasil' => 'Perjalanan dinas berhaisl dihapus']);
    }

    public function submitRequest(Request $request, $id)
    {
        $request->validate([
            'idrequest' => 'required'
        ]);

        $approver = TravelRequest::find($id);

        if (!$approver) {
            return response()->json(['gagal' => 'Data request tidak ditemukan!']);
        }

        $idrequest = $request->idrequest;

        if ($idrequest != 0 && $idrequest != 5) {
            return response()->json(['gagal' => 'ID request tidak valid!']);
        }

        $approver->update([
            'status_approve' => $idrequest,
        ]);
        $statuskirim = 'Perdin';
        $statusMapping = [
            0 => 'Dibatalkan',
            5 => 'Diproses',
        ];
        $statusTampil = $statusMapping[$idrequest] ?? 'Tidak Diketahui';

        $approval = UserMatrixApprovals::where('id_perdin', $id)
            ->where('id_matrix', 1)
            ->where('number', 1)
            ->first();

        if ($approval) {
            $user = User::where('id', $approval->id_user)->first();

            if ($user && $user->email) {
                Mail::to($user->email)->send(new TravelRequestMail($approver, $statusTampil, $user, $statuskirim));
            }
        }

        $message = ($idrequest == 0) 
            ? 'Request berhasil dibatalkan!' 
            : 'Submit request berhasil dikirim!';

        return response()->json([
            'berhasil' => $message,
            'status_approve' => $approver->status_approve,
        ]);
    }


    public function cekStatusApprove($id)
    {
        $request = TravelRequest::find($id);
        
        if ($request) {
            return response()->json(['status_approve' => $request->status_approve]);
        }

        return response()->json(['status_approve' => 0]); // Default ke 0 jika data tidak ditemukan
    }

    public function exportPDF($id)
    {
        $data1= TravelRequest::with('participants.user.karyawan.jabatan','penanggungjawab.user','expenses','categorypf','user','karyawan.jabatan','karyawan.departement','karyawan.user')->where('id',$id)->first();
        $name1 = $data1->name_project;
        $participants = $data1->participants;
        $total = $data1->expenses->sum('total');
        
        $data = [
            'title' => 'FORM PENGAJUAN KASBON PERJALANAN DINAS',
            'no' => '240090/SK/FAD-HMJ/N/2024',
            'name' => $data1->karyawan->user->name,
            'nik' => $data1->karyawan->nik,
            'department' => $data1->karyawan->departement->name ?? '-',
            'jabatan' => $data1->karyawan->jabatan->name ?? '-',
            'no_telepon' => $data1->karyawan->nomortlp ?? '-',
            'nama_project' => $name1 ?? '-',
            'no_so' => $data1->nomorso ?? '-',
            'lokasi_kerja' => $data1->lokasikerja,
            'keperluan' => $data1->keperluan,
            'category_product' => $data1->categorypf->map(fn($cp) => $cp->category->name)->implode(', '),
            'peserta_perjalanan' => $participants,
            'penanggung_jawab' => $data1->penanggungjawab->user->name,
            'estimasi_biaya' => $data1->expenses,
            'total_cash_advance' => $total,
            'tanngal_perdin' => Carbon::parse($data1->created_at)->translatedFormat('d F Y'),
            'terbilang' => 'EMPAT BELAS JUTA RUPIAH',
            'pembon' => [
                'pemohon' => $data1->karyawan->user->name,
                'atasan_langsung' => 'HL',
                'tanggal' => '16/10/2024',
                'disetujui_cso' => 'HL',
                'disetujui_direksi' => '',
            ],
        ];

        $pdf = Pdf::loadView('reports.kasbon_pdf', $data);

        return $pdf->download('kasbon_report.pdf');
    }

    public function ExportEcxelKasbon($id)
    {
        $data1= TravelRequest::with('participants.user.karyawan.jabatan','penanggungjawab.user','expenses','categorypf','user','karyawan.jabatan','karyawan.departement','karyawan.user')->where('id',$id)->first();
        $name1 = $data1->name_project;
        $participants = $data1->participants;
        $total = $data1->expenses->sum('total');
        
        $data = [
            'title' => 'FORM PENGAJUAN KASBON PERJALANAN DINAS',
            'no' => '240090/SK/FAD-HMJ/N/2024',
            'name' => $data1->karyawan->user->name,
            'nik' => $data1->karyawan->nik,
            'department' => $data1->karyawan->departement->name ?? '-',
            'jabatan' => $data1->karyawan->jabatan->name ?? '-',
            'no_telepon' => $data1->karyawan->nomortlp ?? '-',
            'nama_project' => $name1 ?? '-',
            'no_so' => $data1->nomorso ?? '-',
            'lokasi_kerja' => $data1->lokasikerja,
            'keperluan' => $data1->keperluan,
            'category_product' => 'Furniture',
            'peserta_perjalanan' => $participants,
            'penanggung_jawab' => $data1->penanggungjawab->user->name,
            'estimasi_biaya' => $data1->expenses,
            'total_cash_advance' => $total,
            'terbilang' => 'EMPAT BELAS JUTA RUPIAH',
            'pembon' => [
                'pemohon' => $data1->karyawan->user->name,
                'atasan_langsung' => 'HL',
                'tanggal' => '16/10/2024',
                'disetujui_cso' => 'HL',
                'disetujui_direksi' => '',
            ],
        ];

        return Excel::download(new KasbonExport($data), 'kasbon.xlsx');
    }

    public function getKomentar($id)
    {
        $data = TravelRequest::find($id);
        if ($data){
            $komentar = $data->comentar;
            return response()->json(['komentar' => $komentar,]);
        } else {
            return response()->json(['gagal' => 'Data tidak ditemukan!']);
        }

    }

    public function cekStatusApproveDetail($id)
    {
        $request = TravelRequest::find($id);
        
        if ($request) {
            return response()->json(['status_approve_realisasi' => $request->status_approve_realisasi]);
        }

        return response()->json(['status_approve_realisasi' => 0]); // Default ke 0 jika data tidak ditemukan
    }

    public function exportExcel($id)
    {
        return Excel::download(new KasbonExcel($id), 'Kasbon_Report.xlsx');
    }
    

}
