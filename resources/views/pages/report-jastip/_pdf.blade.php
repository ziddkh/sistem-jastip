<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Jastip - {{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #1a1a2e;
        }

        .header p {
            font-size: 12px;
            color: #666;
        }

        .report-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }

        .report-info p {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #435ebe;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f5f5f5;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            background-color: #e3e8f7 !important;
            font-weight: bold;
        }

        .total-row td,
        .total-row th {
            color: #435ebe;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Jastip</h1>
        <p>{{ $title }}</p>
    </div>

    <div class="report-info">
        @if (!empty($dateRange))
        <p><strong>Periode:</strong> {{ $dateRange }}</p>
        @endif
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d M Y H:i') }}</p>
        <p><strong>Total Paket:</strong> {{ count($packages) }} paket</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th>Nama Penerima</th>
                <th>Resi</th>
                <th style="width: 60px;">Berat</th>
                <th style="width: 40px;">P</th>
                <th style="width: 40px;">L</th>
                <th style="width: 40px;">T</th>
                <th style="width: 70px;">KGVOL</th>
            </tr>
        </thead>
        <tbody>
            @if (count($packages) > 0)
            @php
            $totalWeight = 0;
            $totalCubicWeight = 0;
            $totalPrice = 0;
            foreach ($packages as $package) {
            $package->weight = floatval(str_replace(',', '.', $package->weight));
            $package->cubic_weight = floatval(str_replace(',', '.', $package->cubic_weight));

            $totalWeight += $package->weight;
            $totalCubicWeight += $package->cubic_weight;
            }
            @endphp
            <tr class="total-row">
                <td colspan="3" class="text-right">Total</td>
                <td class="text-center">{{ number_format($totalWeight, 2, ',', '.') }}</td>
                <td colspan="3">&nbsp;</td>
                <td class="text-center">{{ number_format($totalCubicWeight, 2, ',', '.') }}</td>
            </tr>
            @foreach ($packages as $package)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $package->name }}</td>
                <td>{{ $package->tracking_number }}</td>
                <td class="text-center">{{ $package->weight }}</td>
                <td class="text-center">{{ $package->length }}</td>
                <td class="text-center">{{ $package->width }}</td>
                <td class="text-center">{{ $package->height }}</td>
                <td class="text-center">{{ $package->cubic_weight }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right">Total</td>
                <td class="text-center">{{ number_format($totalWeight, 2, ',', '.') }}</td>
                <td colspan="3">&nbsp;</td>
                <td class="text-center">{{ number_format($totalCubicWeight, 2, ',', '.') }}</td>
            </tr>
            @else
            <tr>
                <td colspan="9" class="no-data">Tidak ada data</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh Sistem Jastip</p>
    </div>
</body>

</html>