<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return "Ini halaman daftar post (INDEX).";
    }

    public function create()
    {
        return "Form tambah post (CREATE).";
    }

    public function store(Request $request)
    {
        return "Proses simpan post baru (STORE).";
    }

    public function show($id)
    {
        return "Tampilkan detail post dengan ID: " . $id . " (SHOW).";
    }

}
