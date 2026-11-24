<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LaundryFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $kasir;

    protected Package $regulerPackage;

    protected Package $ekspresPackage;

    protected Package $superkilatPackage;

    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin Laundry',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
        ]);

        $this->kasir = User::factory()->create([
            'name' => 'Kasir Laundry',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'kasir',
        ]);

        $this->regulerPackage = Package::create([
            'name' => 'Reguler',
            'price' => 7000,
            'unit' => 'kg',
            'duration' => '3-4 hari',
        ]);

        $this->ekspresPackage = Package::create([
            'name' => 'Ekspres',
            'price' => 12000,
            'unit' => 'kg',
            'duration' => '1-2 hari',
        ]);

        $this->superkilatPackage = Package::create([
            'name' => 'Superkilat',
            'price' => 25000,
            'unit' => 'kg',
            'duration' => '5 jam selesai',
        ]);

        $this->customer = Customer::create([
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'address' => 'Jl. Mawar No. 5',
        ]);
    }

    /**
     * 1. Uji Login dengan role berbeda (admin & kasir).
     */
    public function test_admin_can_login_and_access_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '123',
        ]);

        $this->assertAuthenticatedAs($this->admin);
        $response->assertRedirect('/dashboard');

        $dashResponse = $this->actingAs($this->admin)->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Admin');
    }

    public function test_kasir_can_login_and_access_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'kasir@gmail.com',
            'password' => '123',
        ]);

        $this->assertAuthenticatedAs($this->kasir);
        $response->assertRedirect('/dashboard');

        $dashResponse = $this->actingAs($this->kasir)->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Kasir');
    }

    /**
     * 2. Uji CRUD Layanan (Reguler, Ekspres, Superkilat).
     */
    public function test_admin_can_perform_crud_on_packages(): void
    {
        // CREATE
        $createResponse = $this->actingAs($this->admin)->post('/packages', [
            'name' => 'Cuci Sepatu',
            'price' => 30000,
            'unit' => 'pcs',
            'duration' => '2-3 hari',
            'description' => 'Khusus cuci bersih sneakers',
        ]);
        $createResponse->assertRedirect('/packages');
        $this->assertDatabaseHas('packages', ['name' => 'Cuci Sepatu', 'price' => 30000]);

        $created = Package::where('name', 'Cuci Sepatu')->first();

        // READ
        $readResponse = $this->actingAs($this->admin)->get('/packages');
        $readResponse->assertStatus(200);
        $readResponse->assertSee('Cuci Sepatu');

        // UPDATE
        $updateResponse = $this->actingAs($this->admin)->put("/packages/{$created->id}", [
            'name' => 'Cuci Sepatu Premium',
            'price' => 35000,
            'unit' => 'pcs',
            'duration' => '1 hari',
        ]);
        $updateResponse->assertRedirect('/packages');
        $this->assertDatabaseHas('packages', ['name' => 'Cuci Sepatu Premium', 'price' => 35000]);

        // DELETE
        $deleteResponse = $this->actingAs($this->admin)->delete("/packages/{$created->id}");
        $deleteResponse->assertRedirect('/packages');
        $this->assertDatabaseMissing('packages', ['id' => $created->id]);
    }

    /**
     * 3. Uji Transaksi Laundry: Pencatatan transaksi baru & Relasi.
     */
    public function test_cashier_can_create_laundry_transaction_with_relation(): void
    {
        $response = $this->actingAs($this->kasir)->post('/transactions', [
            'customer_id' => $this->customer->id,
            'package_id' => $this->regulerPackage->id,
            'quantity' => 3.5, // 3.5 kg x 7000 = 24500
            'payment_status' => 'belum_lunas',
            'payment_method' => 'Tunai',
            'notes' => 'Pakaian seragam putih',
        ]);

        $response->assertRedirect('/transactions');

        $this->assertDatabaseHas('transactions', [
            'customer_id' => $this->customer->id,
            'package_id' => $this->regulerPackage->id,
            'user_id' => $this->kasir->id,
            'total_price' => 24500,
            'status' => 'baru',
            'payment_status' => 'belum_lunas',
        ]);

        $transaction = Transaction::latest('id')->first();
        $this->assertEquals('Budi Santoso', $transaction->customer->name);
        $this->assertEquals('Reguler', $transaction->package->name);
    }

    /**
     * 4. Uji Update status pengerjaan dari Proses menjadi Selesai.
     */
    public function test_transaction_status_can_be_updated_from_proses_to_selesai(): void
    {
        $transaction = Transaction::create([
            'invoice_code' => Transaction::generateInvoiceCode(),
            'user_id' => $this->kasir->id,
            'customer_id' => $this->customer->id,
            'package_id' => $this->ekspresPackage->id,
            'quantity' => 2,
            'total_price' => 24000,
            'status' => 'proses',
            'payment_status' => 'belum_lunas',
            'payment_method' => 'Tunai',
        ]);

        // Update status menjadi selesai dan lunas
        $response = $this->actingAs($this->kasir)->put("/transactions/{$transaction->id}", [
            'status' => 'selesai',
            'payment_status' => 'lunas',
            'payment_method' => 'Tunai',
            'notes' => 'Selesai disetrika dan dibungkus rapi',
        ]);

        $response->assertRedirect('/transactions');

        $transaction->refresh();
        $this->assertEquals('selesai', $transaction->status);
        $this->assertEquals('lunas', $transaction->payment_status);
    }

    /**
     * 5. Uji Laporan menampilkan data sesuai relasi.
     */
    public function test_financial_report_displays_data_according_to_relations(): void
    {
        Transaction::create([
            'invoice_code' => Transaction::generateInvoiceCode(),
            'user_id' => $this->kasir->id,
            'customer_id' => $this->customer->id,
            'package_id' => $this->superkilatPackage->id,
            'quantity' => 2,
            'total_price' => 50000,
            'status' => 'selesai',
            'payment_status' => 'lunas',
            'payment_method' => 'Tunai',
        ]);

        $response = $this->actingAs($this->admin)->get('/reports');
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Superkilat');
        $response->assertSee(number_format(50000, 0, ',', '.'));
    }
}
