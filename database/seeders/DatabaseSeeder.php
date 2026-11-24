<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Admin & Kasir)
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Laundry',
                'password' => Hash::make('123'),
                'role' => 'admin',
            ]
        );

        $kasir = User::updateOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'name' => 'Kasir Laundry',
                'password' => Hash::make('123'),
                'role' => 'kasir',
            ]
        );

        // 2. Seed Packages (Layanan Sesuai Permintaan Gambar & Soal)
        $p1 = Package::updateOrCreate(
            ['name' => 'Reguler'],
            [
                'price' => 7000,
                'unit' => 'kg',
                'duration' => '3-4 hari',
                'description' => 'Cuci bersih, wangi, rapi dengan durasi standar 3-4 hari.',
            ]
        );

        $p2 = Package::updateOrCreate(
            ['name' => 'Ekspres'],
            [
                'price' => 12000,
                'unit' => 'kg',
                'duration' => '1-2 hari',
                'description' => 'Layanan cepat prioritas cuci dan setrika selesai dalam 1-2 hari.',
            ]
        );

        $p3 = Package::updateOrCreate(
            ['name' => 'Superkilat'],
            [
                'price' => 25000,
                'unit' => 'kg',
                'duration' => '5 jam selesai',
                'description' => 'Layanan super kilat instan cucian selesai dan siap diambil dalam 5 jam.',
            ]
        );

        $p4 = Package::updateOrCreate(
            ['name' => 'Setrika Saja'],
            [
                'price' => 4000,
                'unit' => 'kg',
                'duration' => '1-2 hari',
                'description' => 'Setrika rapi licin beraroma segar untuk pakaian yang sudah dicuci.',
            ]
        );

        $p5 = Package::updateOrCreate(
            ['name' => 'Bed Cover'],
            [
                'price' => 20000,
                'unit' => 'pcs',
                'duration' => '2-3 hari',
                'description' => 'Cuci khusus bed cover dan selimut tebal dengan higienitas tinggi.',
            ]
        );

        // 3. Seed Pelanggan (Customers)
        $c1 = Customer::updateOrCreate(
            ['phone' => '081234567890'],
            [
                'name' => 'Budi Santoso',
                'address' => 'Jl. Merdeka No. 10, Jakarta Selatan',
            ]
        );

        $c2 = Customer::updateOrCreate(
            ['phone' => '085678901234'],
            [
                'name' => 'Siti Rahma',
                'address' => 'Jl. Mawar No. 5, Jakarta Timur',
            ]
        );

        $c3 = Customer::updateOrCreate(
            ['phone' => '087812345678'],
            [
                'name' => 'Ahmad Fauzi',
                'address' => 'Jl. Anggrek No. 12, Jakarta Barat',
            ]
        );

        $c4 = Customer::updateOrCreate(
            ['phone' => '082198765432'],
            [
                'name' => 'Dewi Lestari',
                'address' => 'Jl. Kenanga No. 8, Jakarta Pusat',
            ]
        );

        // 4. Seed Contoh Transaksi Awal jika belum ada
        if (Transaction::count() === 0) {
            Transaction::create([
                'invoice_code' => 'TRX-'.date('Ymd').'-0001',
                'user_id' => $kasir->id,
                'customer_id' => $c1->id,
                'package_id' => $p1->id,
                'quantity' => 4.5,
                'total_price' => 4.5 * 7000, // 31500
                'status' => 'selesai',
                'payment_status' => 'lunas',
                'payment_method' => 'Tunai',
                'notes' => 'Pakaian kantor rapi jangan terlalu panas setrika.',
            ]);

            Transaction::create([
                'invoice_code' => 'TRX-'.date('Ymd').'-0002',
                'user_id' => $kasir->id,
                'customer_id' => $c2->id,
                'package_id' => $p2->id,
                'quantity' => 3.0,
                'total_price' => 3.0 * 12000, // 36000
                'status' => 'proses',
                'payment_status' => 'lunas',
                'payment_method' => 'QRIS',
                'notes' => 'Ditunggu besok sore jam 4.',
            ]);

            Transaction::create([
                'invoice_code' => 'TRX-'.date('Ymd').'-0003',
                'user_id' => $admin->id,
                'customer_id' => $c3->id,
                'package_id' => $p3->id,
                'quantity' => 2.0,
                'total_price' => 2.0 * 25000, // 50000
                'status' => 'baru',
                'payment_status' => 'belum_lunas',
                'payment_method' => 'Tunai',
                'notes' => 'Superkilat pakaian mau dipakai malam ini.',
            ]);

            Transaction::create([
                'invoice_code' => 'TRX-'.date('Ymd').'-0004',
                'user_id' => $kasir->id,
                'customer_id' => $c4->id,
                'package_id' => $p5->id,
                'quantity' => 2.0,
                'total_price' => 2.0 * 20000, // 40000
                'status' => 'diambil',
                'payment_status' => 'lunas',
                'payment_method' => 'Transfer',
                'notes' => 'Sudah diambil oleh yang bersangkutan.',
            ]);
        }
    }
}
