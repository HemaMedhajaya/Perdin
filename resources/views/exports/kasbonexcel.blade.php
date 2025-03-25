<!DOCTYPE html>
<html>
<head>
</head>
<body style="font-family: 'Times New Roman', Times, serif, Arial, sans-serif; margin: 20px; font-size: 8pt;">

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td colspan="1" style="height: 40px border: 1px solid black;">
                    <img src="{{ public_path('dist/img/logo_stramm (1).webp') }}" style="margin-left: 20px" alt="Logo">
            </td>
            <td colspan="4" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid black;"><span style="font-weight: bold;">PT HEMA MEDHAJAYA</span></td>
            <td colspan="6" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid black;">FORM PENGAJUAN KASBON PERJALANAN DINAS LUAR KOTA</td>
        </tr>

        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px;">Name</th>
            <td colspan="2" style="border: 1px solid black; padding: 8px;">{{ $name }}</td>
            <th colspan="2" style="border: 1px solid black; background-color: #f2f2f2; padding: 8px;">Tanggal Pengajuan</th>
            <td colspan="6" style="border: 1px solid black; padding: 8px;">15 October 2024</td>
        </tr>

        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px;">NIK</th>
            <td colspan="2" style="border: 1px solid black; padding: 8px; text-align: left">{{ $nik }}</td>
            <th colspan="2" style="border: 1px solid black; background-color: #f2f2f2; padding: 8px;">Department</th>
            <td colspan="6" style="border: 1px solid black; padding: 8px;">{{ $department }}</td>
        </tr>

        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px;">Jabatan</th>
            <td colspan="2" style="border: 1px solid black; padding: 8px;">{{ $jabatan }}</td>
            <th colspan="2" style="border: 1px solid black; background-color: #f2f2f2; padding: 8px;">No Telepon</th>
            <td colspan="6" style="border: 1px solid black; padding: 8px;">{{ $no_telepon }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px;">Nama Project</th>
            <td colspan="10" style="border: 1px solid black; padding: 8px;">{{ $nama_project }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px;">Nomor So</th>
            <td colspan="10" style="border: 1px solid black; padding: 8px; text-align: left;">{{ $no_so }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px;">Lokasi Kerja</th>
            <td colspan="10" style="border: 1px solid black; padding: 8px;">{{ $lokasi_kerja }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px;">Keperluan</th>
            <td colspan="10" style="border: 1px solid black; padding: 8px;">{{ $keperluan }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px;">Category Produk</th>
            <td colspan="10" style="border: 1px solid black; padding: 8px;">{{ $category_product }}</td>
        </tr>

        <tr>
            <th colspan="11" style="text-align: center; background-color: #e0e0e0; border: 1px solid black; padding: 8px;">Peserta Perjalanan Dinas</th>
        </tr>

        <tr>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">No</th>
            <th colspan="2" style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Nama</th>
            <th colspan="2" style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Jabatan</th>
            <th colspan="6" style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Penanggung Jawab</th>
        </tr>

        @foreach ($peserta_perjalanan as $index => $peserta)
        <tr>
            <td style="border: 1px solid black; padding: 8px;">{{ $index + 1 }}</td>
            <td colspan="2" style="border: 1px solid black; padding: 8px;">{{ $peserta->user->name }}</td>
            <td colspan="2"style="border: 1px solid black; padding: 8px;">{{ $peserta->user->karyawan->jabatan->name ?? '-' }}</td>
            @if ($loop->first)
                <td colspan="6" style="border: 1px solid black; padding: 8px;">{{ $penanggung_jawab ?? '' }}</td>
            @else
                <td colspan="6" style="border: 1px solid black; padding: 8px;"></td>
            @endif
        </tr>
        @endforeach

        <tr>
            <th colspan="11" style="text-align: center; background-color: #e0e0e0; border: 1px solid black; padding: 8px;">Estimasi Biaya Perjalanan Dinas</th>
        </tr>

        <tr>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Deskripsi</th>
            <th colspan="2" style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Biaya</th>
            <th colspan="2" style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Qty</th>
            <th colspan="2"style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Total</th>
            <th colspan="4"style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Keterangan</th>
        </tr>

        @foreach ($estimasi_biaya as $biaya)
        <tr>
            <td style="border: 1px solid black; padding: 8px;">{{ $biaya->transportation }}</td>
            <td colspan="2" style="border: 1px solid black; padding: 8px; text-align: right;">Rp {{ number_format($biaya->cost, 0, ',', '.') }} x {{ $biaya->man }}</td>
            <td colspan="2" abbr=""style="border: 1px solid black; padding: 8px;">{{ $biaya->quantity }}</td>
            <td colspan="2" style="border: 1px solid black; padding: 8px; text-align: right;">Rp {{ number_format($biaya->total, 0, ',', '.') }}</td>
            <td colspan="4" style="border: 1px solid black; padding: 8px;">{{ $biaya->description }}</td>
        </tr>
        @endforeach

        <tr>
            <th colspan="9" style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">TOTAL CASH ADVANCE (Yang di Bayarkan Finance)</th>
            <td colspan="2" style="border: 1px solid black; padding: 8px; text-align: right;"><strong>Rp {{ number_format($total_cash_advance, 0, ',', '.') }}</strong></td>
        </tr>

        <tr>
            <th rowspan="2" style="text-align: center; vertical-align: middle; 1px solid black; padding: 8px; background-color: #f2f2f2;">Pemohon</th>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;"></th>
            <th colspan="3" style="text-align: center; border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Atasan Langsung</th>
            <th colspan="6" style="text-align: center; border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Disetujui Oleh:</th>
        </tr>
        <tr>
            <td style="border: 1px solid black; background-color: #f2f2f2;"></td>
            <td colspan="3" style="text-align: center; border: 1px solid black; background-color: #f2f2f2;">Manager</td>
            <td colspan="3" style="text-align: center; border: 1px solid black; background-color: #f2f2f2;">CSO</td>
            <td colspan="3" style="text-align: center; border: 1px solid black; background-color: #f2f2f2;">Direksi</td>
        </tr>

        <tr>
            <td style="border: 1px solid black; height: 50px;"></td>
            <td style="border: 1px solid black; height: 50px;"></td>
            <td colspan="3" style="border: 1px solid black;"></td>
            <td colspan="3" style="border: 1px solid black; height: 50px;"></td>
            <td colspan="3" style="border: 1px solid black; height: 50px;"></td>
        </tr>

        <tr>
            <td style="text-align: center; border: 1px solid black; padding: 8px;">{{ $pembon['pemohon'] }}</td>
            <td style="border: 1px solid black; padding: 8px;"></td>
            <td colspan="3" style="text-align: center; border: 1px solid black; padding: 8px;">{{ $pembon['atasan_langsung'] }}</td>
            <td colspan="3" style="text-align: center; border: 1px solid black; padding: 8px;">{{ $pembon['disetujui_cso'] }}</td>
            <td colspan="3" style="text-align: center; border: 1px solid black; padding: 8px;">{{ $pembon['disetujui_direksi'] }}</td>
        </tr>

    </table>
</body>
</html>
