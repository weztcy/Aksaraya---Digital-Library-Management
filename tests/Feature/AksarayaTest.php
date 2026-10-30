<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AksarayaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Aksaraya');
        $response->assertSee('Katalog');
    }

    public function test_catalog_page_loads(): void
    {
        $response = $this->get('/katalog');
        $response->assertStatus(200);
        $response->assertSee('Filter Koleksi');
    }

    public function test_about_page_loads(): void
    {
        $response = $this->get('/tentang');
        $response->assertStatus(200);
        $response->assertSee('Tentang Aksaraya');
    }

    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk');
    }

    public function test_member_can_register(): void
    {
        $response = $this->post('/daftar', [
            'name' => 'Ahmad Dahlan',
            'email' => 'ahmad@example.com',
            'phone' => '081233445566',
            'address' => 'Yogyakarta',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/anggota/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'ahmad@example.com',
            'role' => 'anggota',
        ]);
    }

    public function test_member_can_borrow_book(): void
    {
        // Pick member who hasn't exceeded limits
        $user = User::factory()->create([
            'role' => 'anggota',
            'is_active' => true,
        ]);
        $book = Book::where('stock_available', '>', 0)->first();

        $this->actingAs($user);

        $response = $this->post('/anggota/pinjam', [
            'book_id' => $book->id,
            'notes' => 'Pinjam untuk tugas kuliah',
        ]);

        $response->assertRedirect('/anggota/pinjaman');
        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Pusat Kendali Perpustakaan');
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_member_cannot_access_admin_dashboard(): void
    {
        $member = User::where('role', 'anggota')->first();
        $this->actingAs($member);

        $response = $this->get('/admin/dashboard');
        $response->assertStatus(403);
    }
}

