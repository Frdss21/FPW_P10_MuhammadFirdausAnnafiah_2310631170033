<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Produk - JPG Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            background-color: white;
            color: #000;
            margin: 40px;
        }
        h2, p {
            text-align: center;
            margin: 0;
        }
        h2 {
            font-size: 18pt;
            font-weight: bold;
        }
        p.sub {
            font-size: 13pt;
            margin-top: 5px;
        }
        p.date {
            font-size: 10pt;
            margin-top: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>PT Mahkota Indah Jaya</h2>
    <p class="sub">Rekap Stock Produk Gudang</p>
    <p class="date">Tanggal: {{ date('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Unit</th>
                <th>Type</th>
                <th>Information</th>
                <th>Quantity</th>
                <th>Producer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->information }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->producer }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>