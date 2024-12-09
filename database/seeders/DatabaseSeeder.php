<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // UserTableSeeder::class,
            // ActivitiesTableSeeder::class,
            // ClientTableSeeder::class,
            // AuditTableSeeder::class,
            // DetailAuditTableSeeder::class,
            // DetailCilentTableSeeder::class,
            // DetailCompressorTableSeeder::class,
            // DetailProductInTableSeeder::class,
            // DetailProductOutTableSeeder::class,
            // DetailProductTableSeeder::class,
            // DetailQuotationTableSeeder::class,
            // DetailUsersTableSeeder::class,
            // InvoiceTableSeeder::class,
            // IssuesTableSeeder::class,
            // PaymentTableSeeder::class,
            PICTableSeeder::class,
            ProductInTableSeeder::class,
            ProductTableSeeder::class,
            // QuotationTableSeeder::class,
            ReportsPictTableSeeder::class,
            TargetTableSeeder::class,
            SalesReportsTableSeeder::class,
            SerialProductTableSeeder::class,
        ]);
    }
}
