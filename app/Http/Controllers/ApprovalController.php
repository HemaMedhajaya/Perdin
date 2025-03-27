<?php

namespace App\Http\Controllers;

use App\Exports\KasbonExcel;
use App\Exports\RealisasiExcel;
use App\Mail\ApproveMail;
use App\Models\Categoryproduct;
use App\Models\Karyawan;
use App\Models\PermissionRole;
use App\Models\TravelExpense;
use App\Models\TravelRequest;
use App\Models\User;
use App\Models\UserMatrixApprovals;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ApprovalController extends Controller
{
    protected $name;

    public function __construct()
    {
        $email = session('email');
        if ($email) {
            $this->name = User::where('email', $email)->pluck('name')->first();
        }
    }

    public function dashboard()
    {
        return view('adminapprover.dashboard', ['name' => $this->name]);
    }

    public function index()
    {
        return view('adminapprover.index', ['name' => $this->name]);
    }

    public function getData(Request $request)
    {
        $permissionDetailPerjalananDinas = PermissionRole::getPermission('Detail Approver Perdin', Auth::user()->role);
        $permissionRealisasiPerjalananDinas = PermissionRole::getPermission('Realisasi Approver Perdin', Auth::user()->role);
        $userid = session('user_id');
        
        if ($request->ajax()) {
            // Ambil data TravelRequest yang sesuai dengan filter status
            $data = TravelRequest::with(['user'])
                ->where(function ($query) {
                    $query->where('status_approve', 5)
                        ->orWhere('status_approve_realisasi', 1)
                        ->orWhere('status_approve_realisasi', 2)
                        ->orWhere('status_approve_realisasi', 3)
                        ->orWhere('status_approve_realisasi', 4);
                })
                ->get();
            $data->each(function ($item) use ($userid) {
                $item->usermatrixapproval = UserMatrixApprovals::where('id_user', $userid)
                    ->where('id_perdin', $item->id)
                    ->first();
            });
    
            $filteredData = $data->filter(function ($item) use ($userid) {
                // Ambil semua approval terkait user dan perjalanan dinas ini
                $userApprovals = UserMatrixApprovals::where('id_user', $userid)
                    ->where('id_perdin', $item->id)
                    ->get();
            
                if ($userApprovals->isEmpty()) {
                    return false; // Jika tidak ada approval, jangan tampilkan data
                }
            
                // Kelompokkan data berdasarkan `id_matrix`
                $groupedApprovals = $userApprovals->groupBy('id_matrix');
            
                // Loop setiap grup `id_matrix` dan cek aturan filtering
                foreach ($groupedApprovals as $idMatrix => $approvals) {
                    // Jika ada approval dengan status "Approve", langsung tampilkan
                    if ($approvals->contains('status', 'Approve')) {
                        return true;
                    }
            
                    // Jika ada approval dengan number = 1, langsung tampilkan
                    if ($approvals->contains('number', 1)) {
                        return true;
                    }
            
                    // Ambil approval dengan number terkecil di grup ini
                    $earliestApproval = $approvals->sortBy('number')->first();
            
                    // Cek apakah ada approval sebelumnya yang belum approve dalam grup ini
                    $previousApprovalsExist = UserMatrixApprovals::where('id_perdin', $item->id)
                        ->where('id_matrix', $idMatrix) // Cek dalam grup id_matrix yang sama
                        ->where('number', '<', $earliestApproval->number)
                        ->where('status', '!=', 'Approve')
                        ->exists();
            
                    if (!$previousApprovalsExist) {
                        return true; // Jika semua approval sebelumnya sudah approve, tampilkan
                    }
                }
            
                return false; // Jika semua aturan tidak terpenuhi, jangan tampilkan
            });
            
            
    
            return DataTables::of($filteredData)
                ->addColumn('user_name', function ($data) {
                    return $data->user ? $data->user->name : '-';
                })
                ->addColumn('status_approve', function ($data) use ($userid) {
                    // Ambil UserMatrixApproval berdasarkan id_matrix = 1
                    $userApproval = UserMatrixApprovals::where('id_user', $userid)
                        ->where('id_perdin', $data->id)
                        ->where('id_matrix', 1)
                        ->first();
                
                    // Jika sudah approve di usermatrixapproval, tampilkan "Disetujui"
                    if ($userApproval && $userApproval->status == 'Approve') {
                        return '<span class="badge bg-label-success">Disetujui</span>';
                    }
                
                    $status = [
                        0 => '<span class="badge bg-label-secondary">Draft</span>',
                        5 => '<span class="badge bg-label-warning">Diproses</span>',
                        1 => '<span class="badge bg-label-success">Disetujui</span>',
                        2 => '<span class="badge bg-label-danger">Ditolak</span>',
                    ];
                
                    return $status[$data->status_approve] ?? '<span class="badge bg-label-secondary">Tidak Diketahui</span>';
                })
                
                ->addColumn('status_approve_realisasi', function ($data) use ($userid, $permissionRealisasiPerjalananDinas) {
                    $userApproval = UserMatrixApprovals::where('id_user', $userid)
                        ->where('id_perdin', $data->id)
                        ->where('id_matrix', 2)
                        ->first();
                
                    if (!$userApproval) {
                        return '-';
                    }
                
                    $status = [
                        2 => '<span class="badge bg-label-warning">Diproses</span>',
                        4 => '<span class="badge bg-label-success">Disetujui</span>',
                    ];
                
                    $statusHtml = $status[$data->status_approve_realisasi] ?? '<span class="badge badge-secondary">-</span>';
                
                    $actionRealisasiHtml = '';
                    if ($permissionRealisasiPerjalananDinas > 0) {
                        if (in_array($data->status_approve_realisasi, [2, 3, 4])) {
                            $realisasiUrl = route('approveadmin.realisasi', ['id' => $data->id]);
                            $actionRealisasiHtml = '
                                <a href="' . $realisasiUrl . '" class="btn btn-sm btn-link" data-toggle="tooltip" data-placement="top" title="Realisasi">
                                    <i class="bx bx-calendar-check"></i>
                                </a>
                            ';
                        }
                    }
                
                    if ($userApproval->status == 'Approve') {
                        return '<span class="badge bg-label-success">Disetujui</span> ' . $actionRealisasiHtml;
                    }
                
                    return $statusHtml . ' ' . $actionRealisasiHtml;
                })
                               
                ->addColumn('action', function ($data) use ($permissionDetailPerjalananDinas) {
                    $detailUrl = route('approver.detail', ['id' => $data->id]);
                    $detailButton = '';

                    if ($permissionDetailPerjalananDinas > 0) {
                        $detailButton = '
                            <a href="' . $detailUrl . '" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Detail">
                                <i class="bx bx-detail"></i>
                            </a>
                        ';
                    }
                    return $detailButton;
                })
                ->rawColumns(['status_approve', 'action', 'status_approve_realisasi'])
                ->make(true);
        }
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

    public function edit($id)
    {
        $travelrequest = TravelRequest::with(['user', 'participants', 'penanggungjawab', 'expenses', 'categorypf.category','karyawan', 'karyawan.jabatan','karyawan.departement'])->find($id);

        if (!$travelrequest) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($travelrequest);
    }


    public function submitRequest(Request $request, $id)
    {
        $request->validate([
            'status_approve'
        ]);
        $detail = TravelRequest::findOrFail($id);

        $detail->update([
            'status_approve' => $request->status_approve
        ]);

        return response()->json(['berhasil' => 'Request berhasil dikirim']);
    }
    
    public function realisasiDetail($id)
    {
        return view('adminapprover.realisasi',['name' => $this->name, 'id' => $id]);
    }

    public function getDataCombined($id)
    {
        // Data Sebelum
        $dataSebelum = TravelExpense::with('travelRequest')
            ->where('travel_request_id', $id)
            ->get();
        $travelrequest = TravelRequest::find($id);
        $status_approve_realisasi = $travelrequest->status_approve_realisasi;
        // Data Sesudah
        $dataSesudah = DB::table('travel_expenses as te')
            ->leftJoin('travel_requests as tr', 'te.travel_request_id', '=', 'tr.id')
            ->leftJoin('travel_requests as tr_realisasi', 'te.travel_request_id_realisasi', '=', 'tr_realisasi.id')
            ->where(function($query) use ($id) {
                $query->where('te.travel_request_id', $id)
                    ->orWhere('te.travel_request_id_realisasi', $id);
            })
            ->select('te.*', 'tr.status_approve_realisasi as status_approve_realisasi')
            ->get();

        // Hitung Total Keseluruhan Sebelum Realisasi
        $totalSebelum = $dataSebelum->sum('total');

        // Hitung Total Keseluruhan Setelah Realisasi
        $totalSesudah = $dataSesudah->sum(function($item) {
            return $item->total_realisasi ?? $item->total;
        });

        // Format data agar bisa ditampilkan dalam satu tabel
        $data = [];
        $maxRows = max($dataSebelum->count(), $dataSesudah->count());
        $userid = session('user_id');
        for ($i = 0; $i < $maxRows; $i++) {
            $before = $dataSebelum[$i] ?? null;
            $after = $dataSesudah[$i] ?? null;

            $userApproval = $after ? UserMatrixApprovals::where('id_user', $userid)
            ->where('id_perdin', $travelrequest->id)
            ->where('id_matrix', 2)
            ->first() : null;

            $finalStatus = ($userApproval && $userApproval->status !== 'Not Yet') 
                ? $userApproval->status 
                : ($after->status_approve_realisasi ?? null);

            $statusBadgeMapping = [
                'Approve' => '<span class="badge bg-label-success">Disetujui</span>',
                2 => '<span class="badge bg-label-warning">Diproses</span>',
                1 => '<span class="badge bg-label-secondary">Draft</span>',
                4 => '<span class="badge bg-label-success">Disetujui</span>',
            ];

            $statusBadge = $statusBadgeMapping[$finalStatus] ?? '';

            $data[] = [
                'jenis_perjalanan_sebelum' => $before ? ($before->jenis_perjalanan == 1 ? 'Transportasi' : 'Akomodasi') : '',
                'description_sebelum' => $before ? $before->description : '',
                'total_sebelum' => $before ? $before->total : '',

                'no_sesudah' => $after ? $i + 1 : '',
                'jenis_perjalanan_sesudah' => $after ? ($after->jenis_perjalanan_realisasi == 1 ? 'Transportasi' : 'Akomodasi') : '',
                'description_sesudah' => $after ? ($after->description_realisasi ?? $after->description) : '',
            'total_sesudah' => $after ? ($after->total_realisasi ?? $after->total) : '',
                'status_approve_realisasi' => $statusBadge,
                'action' => $after ? '<button class="btn btn-sm btn-info edit-btn" data-id="'.$after->id.'"><i class="bx bx-edit"></i></button> ' : ''
            ];
        }

        return DataTables::of($data)
            ->with([
                'totalKeseluruhan' => $totalSebelum, 
                'totalSesudah' => $totalSesudah,
                'status_approve_realisasi' => $status_approve_realisasi,
            ])
            ->rawColumns(['action', 'status_approve_realisasi'])
            ->make(true);
    }

    public function updateTravelRequest(Request $request, $id)
    {
        $data = TravelRequest::find($id);

        if (!$data) {
            return response()->json(['gagal' => 'Data tidak ditemukan!']);
        }

        $updateData = ['status_approve_realisasi' => $request->status_approve_realisasi];

        $userid = session('user_id');
        $karyawan = Karyawan::with('user', 'jabatan')->where('user_id', $userid)->first();

        if (!$karyawan || !$karyawan->jabatan) {
            return response()->json(['gagal' => 'Data karyawan atau jabatan tidak ditemukan!']);
        }

        $jabatan = $karyawan->jabatan->name;
        $user = User::find($data->user_id);
        $name = $user->name ?? 'User';
        $statuskirim = 'Realisasi';

        // Jika perjalanan dinas DITOLAK
        if ($request->status_approve_realisasi == 3) {
            $updateData['comentar'] = $request->comentar;
            $data->update($updateData);

            Mail::to($user->email)->send(new ApproveMail($name, 'Ditolak', $data, $jabatan, $statuskirim));

            return response()->json(['berhasil' => 'Perjalanan dinas berhasil ditolak!']);
        }

        // Jika perjalanan dinas DISETUJUI
        if ($request->status_approve_realisasi == 4) {
            $usermatrixapproval = UserMatrixApprovals::where('id_user', $userid)
                ->where('id_matrix', 2)
                ->where('status', 'Not Yet')
                ->where('id_perdin', $id)
                ->orderBy('number', 'asc')
                ->first();

            if ($usermatrixapproval) {
                $hasNextApproval = UserMatrixApprovals::where('id_perdin', $id)
                    ->where('id_matrix', 2)
                    ->where('number', '>', $usermatrixapproval->number)
                    ->where('status', 'Not Yet')
                    ->exists();

                if (!$hasNextApproval) {
                    $data->update($updateData);
                    $usermatrixapproval->update(['status' => 'Approve']);
                    $message = 'Perjalanan dinas berhasil disetujui!';
                } else {
                    $usermatrixapproval->update(['status' => 'Approve']);
                    $message = 'Persetujuan berhasil disimpan. Menunggu approval selanjutnya.';
                }

                // **Kirim email persetujuan**
                Mail::to($user->email)->send(new ApproveMail($name, 'Disetujui', $data, $jabatan, $statuskirim));

                return response()->json(['berhasil' => $message]);
            } else {
                return response()->json(['gagal' => 'Tidak ada approval yang ditemukan!']);
            }
        }

        $data->update($updateData);
        return response()->json(['berhasil' => 'Data perjalanan dinas berhasil diupdate!']);
    }

    public function cekStatusApprove($id)
    {
        $request = TravelRequest::find($id);
        $userid = session('user_id');

        $userApproval = UserMatrixApprovals::where('id_user', $userid)
                    ->where('id_perdin', $request->id)
                    ->where('id_matrix', 2)
                    ->first();
        
        if ($request) {
            return response()->json(['status_approve_realisasi' => $request->status_approve_realisasi,  'userApproval' => $userApproval->status]);
        }

        return response()->json(['status_approve_realisasi' => 0]); // Default ke 0 jika data tidak ditemukan
    }

    public function getDetail($id)
    {
        $data = TravelExpense::find($id);

        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $response = [
            'id' => $data->id,
            'travel_request_id' => $data->travel_request_id_realisasi ?? $data->travel_request_id,
            'jenis_perjalanan' => $data->jenis_perjalanan_realisasi ?? $data->jenis_perjalanan,
            'transportation' => $data->transportation_realisasi ?? $data->transportation,
            'description' => $data->description_realisasi ?? $data->description,
            'cost' => $data->cost_realisasi ?? $data->cost,
            'quantity' => $data->quantity_realisasi ?? $data->quantity,
            'total' => $data->total_realisasi ?? $data->total,
        ];

        return response()->json($response);
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

        $pdf = Pdf::loadView('reports.kasbon_pdf', $data);

        return $pdf->download('kasbon_report.pdf');
    }

    public function exportExcel($id)
    {
        return Excel::download(new KasbonExcel($id), 'Kasbon_Report.xlsx');
    }

    public function exportExcelRealisasi($id)
    {
        return Excel::download(new RealisasiExcel($id), 'Kasbon_Report.xlsx');
    }
}
