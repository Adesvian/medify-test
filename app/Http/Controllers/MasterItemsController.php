<?php

namespace App\Http\Controllers;

use App\Exports\MasterItemsExport;
use App\Models\KategoriItems;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($hargamin) && !empty($hargamax)) {
            $data_search = $data_search->whereBetween('harga_beli', [$hargamin, $hargamax]);
        }
        $data_search = $data_search->select('id', 'kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'foto_product')->with(['kategori:id,nama'])->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = null;
            $selectedCategories = [];
        } else {
            $item = MasterItem::with('kategori')->find($id);
            if (!$item) {
                return redirect('master-items.index')->with('error', 'Item tidak ditemukan');
            }
            $selectedCategories = $item->kategori->pluck('id')->toArray();
        }
        $data = [
            'item' => $item,
            'method' => $method,
            'kategoriItems' => KategoriItems::orderBy('nama')->get(),
            'selectedCategories' => $selectedCategories
        ];

        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::with('kategori')->where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem;

            $lastItem = MasterItem::withTrashed()->latest('id')->first();
            $kode = $lastItem ? (int)substr($lastItem->kode, 0, 5) + 1 : 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $data_item->kode = $kode;
        } else {
            $data_item = MasterItem::findOrFail($id);
            $kode = $data_item->kode;
        }

        if ($request->hasFile('foto_product')) {
            $image = $request->file('foto_product');
            $productName = Str::slug($request->nama);
            $image_name = $productName . '-' . $kode . '.' . $image->getClientOriginalExtension();

            if ($method == 'edit' && $data_item->foto_product) {
                Storage::disk('public')->delete($data_item->foto_product);
            }

            $path = $image->storeAs('products', $image_name, 'public');
            $data_item->foto_product = $path;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        $data_item->save();

        if ($request->has('kategori_ids') && !empty($request->kategori_ids)) {
            $data_item->kategori()->sync($request->kategori_ids);
        } else {
            $data_item->kategori()->detach();
        }


        return redirect('master-items');
    }

    public function delete($id)
    {
        $data = MasterItem::find($id);
        if ($data->foto_product && Storage::disk('public')->exists($data->foto_product)) {
            Storage::disk('public')->delete($data->foto_product);
        }

        $data->kategori()->detach();

        $data->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }

    public function ExportAsExcel()
    {
        try {
            $fileName = 'Master_Items_' . date('Y-m-d_His') . '.xlsx';

            return Excel::download(new MasterItemsExport(), $fileName);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}
