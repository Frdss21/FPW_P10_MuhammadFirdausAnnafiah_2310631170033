<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Route;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Membuat query builder baru untuk model Product
        $query = Product::query();

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
        return view("master-data.product-master.create-product");
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
        $product = Product::findOrFail($id);
        return view('master-data.product-master.edit-product', compact('product'));
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
        ]);

        $product = Product::findOrFail($id);
        $update = $product->update([
            'product_name' => $request->product_name,
            'unit' => $request->unit,
            'type' => $request->type,
            'information' => $request->information,
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
        $product = Product::find(999);

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
        $path = storage_path('app/public/laporan_produk.jpg');

        // ambil tampilan halaman index
        Browsershot::url(route('product-index'))
            ->windowSize(1280, 800)
            ->save($path);

        return response()->download($path);
    }
}
