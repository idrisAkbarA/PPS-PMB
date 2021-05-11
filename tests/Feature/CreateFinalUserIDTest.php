<?php

namespace Tests\Feature;

use App\Ujian;
use App\UserClnMhs;
use App\Library\SoalUjian;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateFinalUserIDTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function testCreateFinalID()
    {
        $this->withoutExceptionHandling();
        $ujian = Ujian::create([
            'periode_id' => 1,
            'user_cln_mhs_id' => 1,
            'is_lunas' => 1,
            'lulus_at' => Carbon::now(),
            'kode_bayar' => "9910010010001",
        ]);
        $this->assertCount(1, Ujian::all());
        $soalUjian = new SoalUjian;
        $soalUjian->setFinalID($ujian->id);

        $ujian = Ujian::find($ujian->id);

        $this->assertEquals('0010010001', $ujian->final_id);
    }
    public function testMassCreateFinalIDByCommand()
    {
        $this->withoutExceptionHandling();
        $ujian = Ujian::create([
            'periode_id' => 1,
            'user_cln_mhs_id' => 1,
            'is_lunas' => 1,
            'lulus_at' => Carbon::now(),
            'kode_bayar' => "9910010010001",
        ]);
        $ujian = Ujian::create([
            'periode_id' => 1,
            'user_cln_mhs_id' => 2,
            'is_lunas' => 2,
            'lulus_at' => Carbon::now(),
            'kode_bayar' => "9910010010002",
        ]);
        $ujian = Ujian::create([
            'periode_id' => 1,
            'user_cln_mhs_id' => 3,
            'is_lunas' => 3,
            'lulus_at' => Carbon::now(),
            'kode_bayar' => "9910010010003",
        ]);
        $this->assertCount(3, Ujian::all());
        $this->artisan('setFinalID');

        $expectedResults = [
            "0010010001",
            "0010010002",
            "0010010003"
        ];
        $index = 0;
        $ujians = Ujian::all();
        foreach ($ujians as $key => $value) {
            $this->assertEquals($expectedResults[$index], $value->final_id);
            $index++;
        }
    }
    public function testCreateFinalIDWhenKodeBayarCreated()
    {
        $this->withoutExceptionHandling();
        $user = new UserClnMhs([
            'nama' => 'enoch',
            'password' => 'yish'
        ]);

        $this->actingAs($user, 'cln_mahasiswa');
        $ujian = Ujian::create([
            'periode_id' => 1,
            'jurusan_id' => 1,
            'user_cln_mhs_id' => 1,
            'is_lunas' => 1,
            'lulus_at' => Carbon::now(),
        ]);
        $this->assertCount(1, Ujian::all());
        $this->assertEquals(1, $ujian->periode_id);
        $this->post('/api/ujian/generate-pembayaran', ['ujian_id' => $ujian->id]);
        $ujian = Ujian::find($ujian->id);
        $this->assertEquals('0010010001', $ujian->final_id);
    }
}
