<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;
use App\Models\Supplier;
use Route;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('supplier');

        // Cek apakah ada parameter 'search' di request
        if ($request->has('search') && $request->search != '') {
            // Melakukan pencarian berdasarkan nama produk
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        // Pagination
        $products = $query->paginate(5);
        return view("master-data.product-master.index-product", compact('products', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view("master-data.product-master.create-product", compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input data
        $validasi_data = $request->validate([
            'product_name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'information' => 'nullable|string',
            'qty' => 'required|integer',
            'producer' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        // Proses simpan data ke database
        $simpan = Product::create($validasi_data);

        // Cek apakah berhasil
        if ($simpan) {
            return redirect()->route('product-index')->with('success', 'Produk berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('error', 'Produk gagal ditambahkan!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('master-data.product-master.detail-product', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $suppliers = Supplier::all();
        $product = Product::findOrFail($id);
        return view('master-data.product-master.edit-product', compact('product', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'information' => 'nullable|string',
            'qty' => 'required|integer|min:1',
            'producer' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $product = Product::findOrFail($id);
        $update = $product->update([
            'product_name' => $request->product_name,
            'unit' => $request->unit,
            'type' => $request->type,
            'information' => $request->input('information'),
            'qty' => $request->qty,
            'producer' => $request->producer,
        ]);

        if ($update) {
            return redirect()->route('product-index')->with('success', 'Produk berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Produk gagal diperbarui!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            return redirect()->back()->with('success', 'Produk berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan!');
    }

    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function exportPDF()
    {
        $products = Product::all();
        $pdf = Pdf::loadView('master-data.product-master.export-pdf', compact('products'));
        return $pdf->download('Laporan_Data_Produk.pdf');
    }

    public function exportJPG()
    {
        $products = Product::all();
        $html = view('master-data.product-master.export-pdf', compact('products'))->render();
        $path = public_path('Laporan_Data_Produk.jpg');

        \Spatie\Browsershot\Browsershot::html($html)
            ->windowSize(1200, 800)
            ->waitUntilNetworkIdle()
            ->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
