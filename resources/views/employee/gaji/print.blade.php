<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $gaji->periode_bayar }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f7fa;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .slip-container {
            background: #fff;
            max-width: 600px;
            margin: 40px auto 0 auto;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44, 62, 80, 0.12);
            padding: 36px 36px 24px 36px;
        }
        .header {
            text-align: center;
            margin-bottom: 32px;
        }
        .company-name {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 1px;
        }
        .slip-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-top: 8px;
            color: #333;
        }
        .periode {
            color: #666;
            font-size: 1rem;
            margin-top: 2px;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .info-block {
            flex: 1;
        }
        .info-label {
            font-size: 0.98rem;
            color: #888;
        }
        .info-value {
            font-size: 1.08rem;
            font-weight: 500;
            color: #222;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .salary-table th, .salary-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }
        .salary-table th {
            background: #f1f5f9;
            color: #2563eb;
            font-size: 1rem;
        }
        .salary-table td {
            font-size: 1.05rem;
        }
        .total-row td {
            font-weight: bold;
            color: #2563eb;
            background: #f0f6ff;
            font-size: 1.15rem;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-block {
            text-align: center;
        }
        .signature-line {
            margin-top: 48px;
            border-top: 1.5px solid #222;
            width: 180px;
            margin-left: auto;
            margin-right: auto;
        }
        .signature-label {
            margin-top: 8px;
            color: #666;
            font-size: 1rem;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .slip-container {
                box-shadow: none;
                margin: 0;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="slip-container">
        <div class="header">
            <div class="company-name">PT. Kehadian Sejahtera</div>
            <div class="slip-title">SLIP GAJI KARYAWAN</div>
            <div class="periode">Periode: <b>{{ $gaji->periode_bayar }}</b></div>
        </div>
        <div class="info-section">
            <div class="info-block">
                <div class="info-label">Nama Karyawan</div>
                <div class="info-value">{{ $gaji->employee->name }}</div>
                <div class="info-label" style="margin-top:10px;">Jabatan</div>
                <div class="info-value">{{ $gaji->employee->position }}</div>
            </div>
            <div class="info-block" style="text-align:right;">
                <div class="info-label">Tanggal Cetak</div>
                <div class="info-value">{{ $gaji->created_at->format('d M Y H:i') }}</div>
            </div>
        </div>
        <table class="salary-table">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Gaji Pokok</td>
                    <td>Rp{{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Tambahan</td>
                    <td>Rp{{ number_format($gaji->komponen_tambahan ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Potongan</td>
                    <td>Rp{{ number_format($gaji->potongan ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Gaji Diterima</td>
                    <td>Rp{{ number_format($gaji->gaji_pokok + ($gaji->komponen_tambahan ?? 0) - ($gaji->potongan ?? 0), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <div class="footer">
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-label">Direktur Keuangan</div>
            </div>
        </div>
        <div class="no-print" style="text-align: center; margin-top: 24px;">
            <button onclick="window.print()" style="padding: 12px 32px; background-color: #2563eb; color: white; border: none; border-radius: 6px; font-size: 1.08rem; font-weight: 500; cursor: pointer;">
                Cetak Slip Gaji
            </button>
        </div>
    </div>
</body>
</html> 