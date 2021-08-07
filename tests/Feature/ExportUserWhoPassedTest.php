<?php

namespace Tests\Feature;

use App\Jurusan;
use App\KatJurusanPerPeriode;
use App\Library\ExportData;
use App\Periode;
use App\Ujian;
use App\UserClnMhs;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExportUserWhoPassedTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function testGetUserWhoPassedExam()
    {
        $kode_bayar = ["9910010010001", "9910010020001"];
        $this->withoutExceptionHandling();
        $this->artisan("initJurusan");
        // $this->artisan("initKategori");
        // $this->artisan('initPeriode');
        $this->createPeriode();
        UserClnMhs::create([
            'nama' => "testA",
            'email' => "testA@test.test",
            'password' => 'password',
            'nik' => "121323123123123",
            'tgl_lahir' => '1997-02-03',
            'tempat_lahir' => 'dumai'
        ]);
        UserClnMhs::create([
            'nama' => "testB",
            'email' => "testB@test.test",
            'password' => 'password',
            'nik' => "121323123123123",
            'tgl_lahir' => '1997-02-04',
            'tempat_lahir' => 'dumai'
        ]);
        $ujian = Ujian::create([
            'periode_id' => 1,
            'jurusan_id' => 1,
            'user_cln_mhs_id' => 1,
            'is_lunas' => 1,
            'lulus_at' => Carbon::now(),
            'kode_bayar' => $kode_bayar[0],
        ]);
        $ujian = Ujian::create([
            'periode_id' => 1,
            'jurusan_id' => 1,
            'user_cln_mhs_id' => 1,
            'is_lunas' => 1,
            'lulus_at' => Carbon::now(),
            'kode_bayar' => $kode_bayar[1],
        ]);
        $this->artisan('setFinalID');
        $data = (ExportData::collection(1)->toArray());

        $expectedValue = [
            [
                'username' => '0010010001',
                'nomor_registrasi' => '0010010001',
                'nama' => 'testA',
                'nik' => '121323123123123',
                'password' => '1997-02-04',
                'tempat_lahir' => 'dumai',
                'tgl_lahir' => '1997-02-03',
                'tahun_ajaran' => Carbon::now()->year,
                'kode_jurusan' => Jurusan::find(1)->kode_jurusan,
                'id_jurusan' => 1,
                'jalur_masuk' => 12
            ],
            [
                'username' => '0010020001',
                'nomor_registrasi' => '0010020001',
                'nama' => 'testB',
                'nik' => '121323123123123',
                'password' => '1997-02-04',
                'tempat_lahir' => 'dumai',
                'tgl_lahir' => '1997-02-04',
                'tahun_ajaran' => Carbon::now()->year,
                'kode_jurusan' => Jurusan::find(2)->kode_jurusan,
                'id_jurusan' => 2,
                'jalur_masuk' => 12
            ]
        ];
        $this->assertCount(2, $data);
        $this->assertEquals($expectedValue, $data);
    }
    public function createPeriode()
    {
        $thisYear =  Carbon::now()->year;
        $nextYear = Carbon::now()->addYear()->year;
        DB::table('periodes')->insert(
            [
                "nama" => $thisYear . "/" . $nextYear,
                "awal_periode" => $thisYear . '-01-01',
                "akhir_periode" => $thisYear . '-12-30',
                "tahun" => $thisYear,
                "range_ujian" => 10,
                "durasi_ujian" => 5,
                "durasi_soal" => 15,
                "syarat_ipk" => 3.0,
                "syarat_bhs_arab" => 350,
                "syarat_bhs_inggris" => 350,
                "awal_temu_ramah" => $thisYear . '-5-01',
                "akhir_temu_ramah" => $thisYear . '-5-30',
                "jumlah_tka" => 10,
                "jumlah_tkj" => 10,
                "min_lulus_tka" => 5,
                "min_lulus_tkj" => 5,
                "is_Active" => true,
            ]
        );
    }
}
