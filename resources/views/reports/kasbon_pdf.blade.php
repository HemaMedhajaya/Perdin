<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif, Arial, sans-serif;
            margin: 20px;
            font-size: 8pt;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .logo {
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <table>
        <!-- Baris 1: Logo dan Judul -->
        <tr>
            <!-- Gabungkan 2 kolom untuk logo dan PT HEMA MEDHAJAYA -->
            <td colspan="2" style="border: none;">
                <div style="display: flex; align-items: center;">
                    <!-- Logo -->
                    <img src="{{ public_path('dist/img/logo_stramm.jpg') }}" class="logo" alt="Logo" style="margin-right: 30px;">
                    <!-- Teks PT HEMA MEDHAJAYA -->
                    <span style="font-weight: bold;">PT HEMA MEDHAJAYA</span>
                </div>
            </td>
            <td colspan="3" style="text-align: center;">
                <span>FORM PENGAJUAN KASBON PERJALANAN DINAS LUAR KOTA</span>
            </td>
        </tr>

        <tr>
            <th>Name</th>
            <td>{{ $name }}</td>
            <th>Tanggal Pengajuan</th>
            <td colspan="2">{{ $tanngal_perdin }}</td>
        </tr>

        <tr>
            <th>NIK</th>
            <td>{{ $nik }}</td>
            <th>Department</th>
            <td colspan="2">{{ $department }}</td>
        </tr>

        <tr>
            <th>Jabatan</th>
            <td>{{ $jabatan }}</td>
            <th>No Telepon</th>
            <td colspan="2">{{ $no_telepon }}</td>
        </tr>

        <tr>
            <th>Nama Project</th>
            <td colspan="4">{{ $nama_project }}</td>
        </tr>

        <tr>
            <th>No SO</th>
            <td colspan="4">{{ $no_so }}</td>
        </tr>

        <tr>
            <th>Lokasi Kerja</th>
            <td colspan="4">{{ $lokasi_kerja }}</td>
        </tr>

        <tr>
            <th>Keperluan</th>
            <td colspan="4">{{ $keperluan }}</td>
        </tr>

        <tr>
            <th>Category Product</th>
            <td colspan="4">{{ $category_product }}</td>
        </tr>

        <tr>
            <th colspan="5" style="text-align: center; background-color: #e0e0e0;">Peserta Perjalanan Dinas</th>
        </tr>

        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th colspan="2">Penanggung Jawab</th>
        </tr>

        @foreach ($peserta_perjalanan as $index => $peserta)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $peserta->user->name}}</td>
                <td>{{ $peserta->user->karyawan->jabatan->name ?? '-' }}</td>
                @if ($loop->first) 
                    <td colspan="2">{{ $penanggung_jawab ?? '' }}</td>
                @else
                    <td colspan="2"></td> 
                @endif                                      
            </tr>
        @endforeach

        <tr>
            <th colspan="5" style="text-align: center; background-color: #e0e0e0;">Estimasi Biaya Perjalanan Dinas</th>
        </tr>

        <tr>
            <th>Deskripsi</th>
            <th>Biaya</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Keterangan</th>
        </tr>

        @foreach ($estimasi_biaya as $biaya)
            <tr>
                <td>{{ $biaya->transportation }}</td>
                <td class="text-right">Rp {{ number_format($biaya->cost, 0, ',', '.') }} x {{ $biaya->man }}</td>
                <td>{{ $biaya->quantity }}</td>
                <td class="text-right">{{ $biaya->total }}</td>
                <td>{{ $biaya->description }}</td>
            </tr>
        @endforeach

        <tr>
            <th colspan="4">TOTAL CASH ADVANCE (Yang di Bayarkan Finance)</th>
            <td class="text-right"><strong>Rp {{ number_format($total_cash_advance, 0, ',', '.') }}</strong></td>
        </tr>

        <tr>
            <th rowspan="2">Pemohon</th>
            <th colspan="1">Atasan Langsung:</th>
            <th colspan="1" ></th>
            <th colspan="2">Disetujui Oleh:</th>
        </tr>
        <tr>
            <th>Manager</th>
            <th></th>
            <th>CSO</th>
            <th>Direksi</th>
        </tr>
        <tr>
            <td style="height: 50px;"></td> 
            <td style="height: 50px;"></td>
            <td></td>
            <td style="height: 50px;"></td>
            <td style="height: 50px;"></td>
        </tr>   
        <tr>
            <td>{{ $pembon['pemohon'] }}</td>
            <td>{{ $pembon['atasan_langsung'] }}</td>
            <td></td>
            <td>{{ $pembon['disetujui_cso'] }}</td>
            <td>{{ $pembon['disetujui_direksi'] }}</td>
        </tr>
        
    </table>
</body>
</html>