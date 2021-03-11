<?php

namespace App\Http\Controllers;

use App\BankSoal;
use App\Imports\SoalImport;
use App\Exports\templateExport;
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
}
