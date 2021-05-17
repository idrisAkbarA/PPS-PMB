<?php

namespace Tests\Feature;

use App\Ujian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetLulusCumlaudeCountTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function testMassSetLulusCumlaude()
    {
        Ujian::create([
            'periode_id' => 1,
            'jurusan_id' => 1,
            'user_cln_mhs_id' => 1,
            'is_jalur_cumlaude' => 1,
            'is_lulus_tka' => 1,
            'is_lulus_tkj' => 1,
            'is_lunas' => 1
        ]);
        Ujian::create([
            'periode_id' => 1,
            'jurusan_id' => 1,
            'user_cln_mhs_id' => 2,
            'is_jalur_cumlaude' => 1,
            'is_lulus_tka' => 1,
            'is_lulus_tkj' => 1,
            'is_lunas' => 1
        ]);
        Ujian::create([
            'periode_id' => 1,
            'jurusan_id' => 1,
            'user_cln_mhs_id' => 3,
            'is_jalur_cumlaude' => 1,
            'is_lulus_tka' => 0,
            'is_lulus_tkj' => 0,
            'is_lunas' => 1
        ]);
        $this->assertCount(3, Ujian::all());
        $this->artisan('massSetLulusCumlaude');
        $newUjians = Ujian::all();
        // dd($newUjians);
        $this->assertNotNull($newUjians[0]->lulus_at, "id: " . $newUjians[0]->id);
        $this->assertNotNull($newUjians[1]->lulus_at);
    }
}
