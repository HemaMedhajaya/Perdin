<table>
    <thead>
        <tr>
            <th rowspan="2" style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold; background-color: #ffd8a8; text-align: center; vertical-align: middle; font-size: 6pt; width: 30px;">No</th>
            <th colspan="4" style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold; background-color: #ffd8a8; text-align: center; width: 150px; font-size: 6pt;">BUDGET 1</th>
            <th rowspan="2" style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold; background-color: #ffd8a8; text-align: center; vertical-align: middle; font-size: 6pt; width: 70px;">TOTAL BUDGET</th>
            <th colspan="6" style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold; background-color: #c3e6cb; text-align: center; font-size: 6pt;">TANAP 1</th>
            <th rowspan="2" style="border: 1px solid black; background-color: #a7c7e7; font-weight: bold; text-align: center; vertical-align: middle; font-size: 6pt; font-size: 6pt; width: 65px;">TOTAL REALISASI</th>
            <th rowspan="2" style="border: 1px solid black; background-color: #a7c7e7; font-weight: bold; text-align: center; vertical-align: middle; font-size: 6pt; font-size: 6pt; width: 65px;">KETERANGAN</th>
            <th rowspan="2" style="border: 1px solid black; background-color: #f2f2f2; font-weight: bold; text-align: center; vertical-align: middle; font-size: 6pt; font-size: 6pt; width: 65px;">BUDGET - PERDIN</th>
        </tr>
        <tr>
            <th style="border: 1px solid black; background-color: #ffd8a8;  font-weight: bold; text-align: center; font-size: 6pt;">DELIVERY</th>
            <th style="border: 1px solid black; background-color: #ffd8a8;  font-weight: bold; text-align: center; font-size: 6pt; width: 30px;">MAN</th>
            <th style="border: 1px solid black; background-color: #ffd8a8;  font-weight: bold; text-align: center; font-size: 6pt; width: 30px;">DAY</th>
            <th style="border: 1px solid black; background-color: #ffd8a8;  font-weight: bold; text-align: center; font-size: 6pt; width: 50px;">AMOUNT</th>
            <th style="border: 1px solid black; background-color: #c3e6cb;  font-weight: bold; text-align: center; font-size: 6pt; width: 50px;">PIC</th>
            <th style="border: 1px solid black; background-color: #c3e6cb;  font-weight: bold; text-align: center; font-size: 6pt;">TGL PERGI</th>
            <th style="border: 1px solid black; background-color: #c3e6cb;  font-weight: bold; text-align: center; font-size: 6pt;">TGL PULANG</th>
            <th style="border: 1px solid black; background-color: #c3e6cb;  font-weight: bold; text-align: center; font-size: 6pt; width: 30px;">MAN</th>
            <th style="border: 1px solid black; background-color: #c3e6cb;  font-weight: bold; text-align: center; font-size: 6pt; width: 30px;">DAY</th>
            <th style="border: 1px solid black; background-color: #c3e6cb;  font-weight: bold; text-align: center; font-size: 6pt; width: 50px;">TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @php 
        $no = 1; 
        @endphp
        @foreach ($dataGabungan as $item)
            <tr>
                <td style="border: 1px solid black; text-align: center; font-size: 6px; ">{{ $no++ }}</td>
                <td style="border: 1px solid black; font-size: 6px;">
                    {{ $item['sebelum'] ? $item['sebelum']->travelRequest->name_project : '' }}
                </td>
                <td style="border: 1px solid black; text-align: center; font-size: 6px; ">{{ $item['sebelum'] ? $item['sebelum']->man : '' }}</td>
                <td style="border: 1px solid black; text-align: center; font-size: 6px; ">{{ $item['sebelum'] ? $item['sebelum']->quantity : '' }}</td>
                <td style="border: 1px solid black; text-align: center; font-size: 6px;">
                    {{ isset($item['sebelum']->total) ? 'Rp ' . number_format($item['sebelum']->total, 0, ',', '.') : '' }}
                </td>
        
                <td style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;" >
                    {{ $loop->first && $item['sebelum'] ? 'Rp ' . number_format($totalsebelum, 0, ',', '.') : '' }}
                </td>
        
                <td style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">
                    {{ $loop->first && $item['sebelum'] ? $item['sebelum']->travelrequest->penanggungjawab->user->name : '' }}
                </td>
        
                <td style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">
                    {{ $loop->first && $item['sebelum'] ? \Carbon\Carbon::parse($item['sebelum']->created_at)->format('d F Y') : '' }}
                </td>
        
                <td style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">{{ $loop->first && $item['sebelum'] ? \Carbon\Carbon::parse($item['sebelum']->updated_at)->format('d F Y') : '' }}</td>
                <td style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">{{ $item['sesudah'] ? $item['sesudah']->man_realisasi ?? $item['sesudah']->man_realisasi: '' }}</td>
                <td style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">{{ $item['sesudah'] ? $item['sesudah']->quantity_realisasi ?? $item['sesudah']->quantity: '' }}</td>
                <td style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">{{ $item['sesudah'] ? 'Rp ' . number_format($item['sesudah']->total_realisasi ?? $item['sesudah']->total, 0, ',', '.') : '' }}</td>
                <td style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;" >
                    {{ $loop->first && $item['sesudah'] ? 'Rp ' . number_format($totalsesudah, 0, ',', '.') : '' }}
                </td>
        
                <td style="border: 1px solid black; text-align: center; font-size: 6px; ">
                    @if ($item['sesudah'])
                        {!! [
                            2 => '<span style="color:orange;">Diproses</span>',
                            1 => '<span style="color:gray;">Draft</span>',
                            3 => '<span style="color:red;">Ditolak</span>',
                            4 => '<span style="color:green;">Disetujui</span>',
                        ][$item['sesudah']->status_approve_realisasi] ?? '' !!}
                    @endif
                </td>
                <td style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;" >
                    {{ $loop->first && $item['sesudah'] ? 'Rp ' . number_format($totalsesudah, 0, ',', '.') : '' }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5" style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">Pemohon</td>
            <td colspan="7" style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">Mengetahui</td>
            <td colspan="3" style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">Bod</td>
        </tr>
        <tr>
            <td colspan="5" style="height: 40px; border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;"></td>
            <td colspan="7" style="height: 40px; border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;"></td>
            <td colspan="3" style="height: 40px; border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;"></td>
        </tr>
        <tr>
            <td colspan="5" style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">Frontemnt</td>
            <td colspan="7" style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">TH</td>
            <td colspan="3" style="border: 1px solid black; text-align: center; font-size: 6px; vertical-align: middle;">TH</td>
        </tr>
    
    </tbody>
</table>
    