<!DOCTYPE html>
<html>
<head>
</head>
<body style="font-family: 'Times New Roman', Times, serif, Arial, sans-serif; margin: 20px; font-size: 8pt;">

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td colspan="1" style="height: 40px border: 1px solid black; font-size: 7pt;">
                    <img src="{{ public_path('dist/img/logo_stramm (1).webp') }}" style="margin-left: 20px" alt="Logo">
            </td>
            <td colspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid black; width: 110px; font-size: 7pt;"><span style="font-weight: bold;">PT HEMA MEDHAJAYA</span></td>
            <td colspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid black; width: 150px; font-size: 7pt;">FORM PENGAJUAN KASBON PERJALANAN DINAS LUAR KOTA</td>
        </tr>

        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px; font-size: 7pt;">Name</th>
            <td style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $name }}</td>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px; width: 80px; font-size: 7pt;">Tanggal Pengajuan</th>
            <td colspan="2" style="border: 1px solid black; padding: 8px; font-size: 7pt;">15 October 2024</td>
        </tr>

        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px; font-size: 7pt;">NIK</th>
            <td style="border: 1px solid black; padding: 8px; text-align: left; font-size: 7pt;">{{ $nik }}</td>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px; font-size: 7pt;">Department</th>
            <td colspan="2" style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $department }}</td>
        </tr>

        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px; font-size: 7pt;">Jabatan</th>
            <td style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $jabatan }}</td>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px; font-size: 7pt;">No Telepon</th>
            <td colspan="2" style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $no_telepon }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px; font-size: 7pt;">Nama Project</th>
            <td colspan="4" style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $nama_project }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px; font-size: 7pt;">Nomor So</th>
            <td colspan="4" style="border: 1px solid black; padding: 8px; text-align: left; font-size: 7pt;">{{ $no_so }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px; font-size: 7pt;">Lokasi Kerja</th>
            <td colspan="4" style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $lokasi_kerja }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px; font-size: 7pt;">Keperluan</th>
            <td colspan="4" style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $keperluan }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid black; background-color: #f2f2f2; padding: 8px; font-size: 7pt;">Category Produk</th>
            <td colspan="4" style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $category_product ? : '-'}}</td>
        </tr>

        <tr>
            <th colspan="5" style="text-align: center; background-color: #e0e0e0; border: 1px solid black; padding: 8px; font-size: 7pt;">Peserta Perjalanan Dinas</th>
        </tr>

        <tr>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">No</th>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">Nama</th>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">Jabatan</th>
            <th colspan="2" style="border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">Penanggung Jawab</th>
        </tr>

        @foreach ($peserta_perjalanan as $index => $peserta)
        <tr>
            <td style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $index + 1 }}</td>
            <td style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $peserta->user->name }}</td>
            <td style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $peserta->user->karyawan->jabatan->name ?? '-' }}</td>
            @if ($loop->first)
                <td colspan="2" style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $penanggung_jawab ?? '' }}</td>
            @else
                <td colspan="2" style="border: 1px solid black; padding: 8px; font-size: 7pt;"></td>
            @endif
        </tr>
        @endforeach

        <tr>
            <th colspan="5" style="text-align: center; background-color: #e0e0e0; border: 1px solid black; padding: 8px; font-size: 7pt;">Estimasi Biaya Perjalanan Dinas</th>
        </tr>

        <tr>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">Deskripsi</th>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">Biaya</th>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">Qty</th>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">Total</th>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2; width: 100px; font-size: 7pt;">Keterangan</th>
        </tr>

        @foreach ($estimasi_biaya as $biaya)
        <tr>
            <td style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $biaya->transportation }}</td>
            <td style="border: 1px solid black; padding: 8px; text-align: right; font-size: 7pt;">Rp {{ number_format($biaya->cost, 0, ',', '.') }} x {{ $biaya->man }}</td>
            <td abbr=""style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $biaya->quantity }}</td>
            <td style="border: 1px solid black; padding: 8px; text-align: right; font-size: 7pt;">Rp {{ number_format($biaya->total, 0, ',', '.') }}</td>
            <td style="border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $biaya->description }}</td>
        </tr>
        @endforeach

        <tr>
            <th colspan="4" style="border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">TOTAL CASH ADVANCE (Yang di Bayarkan Finance)</th>
            <td colspan="1" style="border: 1px solid black; padding: 8px; text-align: right; font-size: 7pt;"><strong>Rp {{ number_format($total_cash_advance, 0, ',', '.') }}</strong></td>
        </tr>

        <tr>
            <th style="text-align: center; border: 1px solid black; vertical-align: middle; 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">Pemohon</th>
            <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;"></th>
            <th style="text-align: center; border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">Atasan Langsung</th>
            <th colspan="2" style="text-align: center; border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-size: 7pt;">Disetujui Oleh:</th>
        </tr>
        <tr>
            <td style="border: 1px solid black; background-color: #f2f2f2; font-size: 7pt;"></td>
            <td style="text-align: center; border: 1px solid black; background-color: #f2f2f2; font-size: 7pt;">Manager</td>
            <td style="text-align: center; border: 1px solid black; background-color: #f2f2f2; font-size: 7pt;">CSO</td>
            <td colspan="2" style="text-align: center; border: 1px solid black; background-color: #f2f2f2; font-size: 7pt;">Direksi</td>
        </tr>

        <tr>
            <td style="border: 1px solid black; height: 50px; font-size: 7pt;"></td>
            <td style="border: 1px solid black; height: 50px; font-size: 7pt;"></td>
            <td style="border: 1px solid black; font-size: 7pt;"></td>
            <td style="border: 1px solid black; height: 50px; font-size: 7pt;"></td>
            <td style="border: 1px solid black; height: 50px; font-size: 7pt;"></td>
        </tr>

        <tr>
            <td style="text-align: center; border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $pembon['pemohon'] }}</td>
            <td style="border: 1px solid black; padding: 8px; font-size: 7pt;"></td>
            <td style="text-align: center; border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $pembon['atasan_langsung'] }}</td>
            <td style="text-align: center; border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $pembon['disetujui_cso'] }}</td>
            <td style="text-align: center; border: 1px solid black; padding: 8px; font-size: 7pt;">{{ $pembon['disetujui_direksi'] }}</td>
        </tr>

    </table>
</body>
</html>
