<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Mutasi Stock Bulanan (PDF)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 40px;
            background: white;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .header img {
            width: 70px;
            height: auto;
        }

        .header-title {
            flex: 1;
            text-align: center;
            margin-right: 70px; 
        }

        .header-title h2 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
        }

        .header-title h3 {
            margin: 3px 0 5px 0;
            font-size: 13pt;
            font-weight: bold;
        }

        .header-title p {
            margin: 0;
            font-style: italic;
            font-size: 11pt;
        }

        /* TABEL DATA */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px;
        }

        th {
            background-color: #d9ead3;
            text-align: center;
            font-weight: bold;
        }

        td {
            vertical-align: top;
        }

        /* FOOTER */
        footer {
            margin-top: 40px;
            font-size: 11px;
        }

        .footer-sign {
            margin-top: 60px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo.jpeg') }}" alt="Logo">
        <div class="header-title">
            <h2>PT UUS MIRACLE</h2>
            <h3>REKAP MUTASI STOCK BULANAN</h3>
            <p>Periode: {{ \Carbon\Carbon::now()->startOfMonth()->format('d M Y') }}
                s/d {{ \Carbon\Carbon::now()->endOfMonth()->format('d M Y') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Unit</th>
                <th>Category</th>
                <th>Description</th>
                <th>Stock</th>
                <th>Supplier</th>
                <th>Barang Masuk</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->information }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->producer }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        <p>* Data ini bersifat rahasia.</p>
        <p>Tanggal cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>

        <div class="footer-sign">
            <p>Diketahui,</p>
            <br><br><br>
            <p>Muhammad Firdaus Annafiah,S.Kom</p>
        </div>
    </footer>
</body>
</html>