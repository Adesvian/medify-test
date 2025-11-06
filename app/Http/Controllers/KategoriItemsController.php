<?php

namespace App\Http\Controllers;

use App\Models\KategoriItems;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KategoriItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('kategori_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = KategoriItems::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        $data_search = $data_search->select('kode', 'nama',)->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($method = 'new', $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = KategoriItems::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        return view('kategori_items.form.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new KategoriItems;
            $kode = KategoriItems::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = KategoriItems::find($id);
            $kode = $data_item->kode;
        }

        $data_item->kode = $kode;
        $data_item->nama = $request->nama;

        $data_item->save();

        return redirect('kategori-items');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($kode)
    {
        $data = KategoriItems::with('master')->where('kode', $kode)->firstOrFail();
        return view('kategori_items.single.index', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = KategoriItems::find($id);
        if ($data->master()->count() > 0) {
            return redirect('kategori-items/view/' . $data->kode)->with('error', 'Data ini masih digunakan di master item');
        }

        $data->delete();
        return redirect('master-items');
    }

    public function exportAsPDF($id)
    {
        $data = KategoriItems::with('master')->findOrFail($id);

        $data = [
            'kategori' => $data,
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
            'waktu' => Carbon::now()->translatedFormat('H:i:s')
        ];

        $pdf = Pdf::loadView('kategori_items.template.pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);

        return $pdf->download('Kategori_' . $data['kategori']->nama . '-' . $data['kategori']->kode . '_' . date('YmdHis') . '.pdf');
    }
}
