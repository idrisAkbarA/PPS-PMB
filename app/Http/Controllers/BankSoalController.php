<?php

namespace App\Http\Controllers;

use App\BankSoal;
use App\Imports\SoalImport;
use App\Exports\templateExport;
use App\Jurusan;
use App\Kategori;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class BankSoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function importExcel(Request $request)
    {
        $import = new SoalImport;
        Excel::import($import, $request->file('file'));

        return response()->json([
            'status' => "Success: Soal Added",
            'total' => $import->getRowCount(),
            'error' => $import->getError()
        ]);
    }
    public function generateTemplate()
    {
        // $spreadsheet = new Spreadsheet();
        // $spreadsheet = new Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setCellValue('A1', 'Hello World !');

        // $writer = new Xls($spreadsheet);
        // $writer->save('hello world.xls');
        return Excel::download(new templateExport, 'template.xlsx');
    }
    public function index(Request $request)
    {
        $jurusan_id = $request->jurusan;
        $kategori_id = $request->kategori;

        $soal = BankSoal::getByKategori($kategori_id, $jurusan_id);

        return response()->json($soal, 200);
    }

    public function getSoalTKA(Request $request)
    {
        $jurusan_id = $request->jurusan;
        $kategori_id = $request->kategori;

        $soal = BankSoal::getTkaByKategori($kategori_id, $jurusan_id);

        return response()->json($soal, 200);
    }

    public function getSoalTKJ(Request $request)
    {
        $jurusan_id = $request->jurusan;
        $kategori_id = $request->kategori;

        $soal = BankSoal::getTkjByKategori($kategori_id, $jurusan_id);

        return response()->json($soal, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $soal = BankSoal::create($request->all());

        $reply = [
            'status' => true,
            'message' => 'Soal Successfully Created!',
            'data' => $soal
        ];
        return response()->json($reply, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankSoal  $soal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankSoal $soal)
    {
        $soal->update($request->all());

        $reply = [
            'status' => true,
            'message' => 'Soal Successfully Updated!',
            'data' => $soal
        ];
        return response()->json($reply, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankSoal  $soal
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankSoal $soal)
    {
        $soal->delete();

        $reply = [
            'status' => true,
            'message' => 'Soal Successfully Deleted!',
        ];
        return response()->json($reply, 200);
    }
    public function jumlah()
    {
        $bank_soal = BankSoal::all();
        $jurusans = Jurusan::all();
        $kategoris = Kategori::all();

        $result = [];
        foreach ($jurusans as $key => $value) {
            $jumlah_kat = [];
            // 1. get all data for each jurusan 
            $kategori_temp = $kategoris->where('jurusan_id', $value['id']);
            $soal_temp = $bank_soal->where('jurusan_id', $value['id']);
            // 2. count them
            $jumlah_soal_jurusan = count($soal_temp);
            $jumlah_kategori_jurusan = count($kategori_temp);
            // count each kategori for every jurusan
            // an store it in $jumlah_kat
            foreach ($kategori_temp as $kat_key => $kat_value) {
                $jumlah_kat_temp = count($soal_temp->where('kategori_id', $kat_value['id']));
                array_push($jumlah_kat, [
                    'kategori' => $kat_value['nama'],
                    'jumlah' => $jumlah_kat_temp
                ]);
            }
            $temp =
                [
                    'jurusan' => $value['nama'],
                    'kategori' => $jumlah_kat,
                    'jumlah_soal_total' => $jumlah_soal_jurusan,
                    'jumlah_kategori' => $jumlah_kategori_jurusan,
                ];
            array_push($result, $temp);
        }
        $result = [
            'total' => count($bank_soal),
            'detail' => $result,
        ];
        return response()->json($result);
    }
}
