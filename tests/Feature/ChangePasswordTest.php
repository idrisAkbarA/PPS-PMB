<?php

namespace Tests\Feature;

use App\UserClnMhs;
use App\Userpetugas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_change_password_cln_mhs()
    {
        $this->withoutExceptionHandling();
        UserClnMhs::create([
            'nama' => "test",
            'email' => "test@test.test",
            'password' => 'old_password',
        ]);
        $response = $this->put('/api/change-password', [
            'username' => 'test@test.test',
            'old_password' => 'old_password',
            'new_password' => 'new_password',
        ]);
        $user = UserClnMhs::first();

        $this->assertTrue(Hash::check('new_password', $user->password), $response['message']);
    }
    public function test_change_password_petugas()
    {
        $this->withoutExceptionHandling();
        UserPetugas::create([
            'nama' => "admin",
            'username' => "admintest",
            'role' => 1,
            'password' => 'old_password',
        ]);
        $this->assertCount(1, UserPetugas::all());
        $response = $this->put('/api/change-password', [
            'username' => 'admintest',
            'old_password' => 'old_password',
            'new_password' => 'new_password',
        ]);
        $user = UserPetugas::first();

        $this->assertTrue(Hash::check('new_password', $user->password), $response['message']);
    }
}
