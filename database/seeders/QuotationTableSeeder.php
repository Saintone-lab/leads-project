<?php

namespace Database\Seeders;

use App\Models\Quotation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuotationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quotation = [
            [
                "id_client"=> "1",
                "id_sales"=> "2",
                "id_service"=> NULL,
                "status"=> "Draft",
                "expired_date"=> \Carbon\Carbon::today()->addDays(14)->format('Y-m-d H:i:s'),
                "folup_date"=> \Carbon\Carbon::today()->addDays(7)->format('Y-m-d H:i:s'),
                "no_quote"=> "RJO-XIII-2023-121",
                "termcon"=> "Validity Of Quotation: 1 Month since the Date of Quotation",
                "tax"=> "12",
                "shipping"=> "50000",
                "subtotal"=> "1500000",
                "harga_total"=> "1750000",
            ],
            [
                "id_client"=> "1",
                "id_sales"=> "2",
                "id_service"=> NULL,
                "status"=> "Send",
                "expired_date"=> \Carbon\Carbon::today()->addDays(28)->format('Y-m-d H:i:s'),
                "folup_date"=> \Carbon\Carbon::today()->addDays(21)->format('Y-m-d H:i:s'),
                "no_quote"=> "RJO-XIII-2023-122",
                "termcon"=> "Validity Of Quotation: 1 Month since the Date of Quotation",
                "tax"=> "12",
                "shipping"=> "50000",
                "subtotal"=> "3000000",
                "harga_total"=> "3110000",
            ],
            [
                "id_client"=> "2",
                "id_sales"=> "2",
                "id_service"=> NULL,
                "status"=> "Negotiation",
                "expired_date"=> \Carbon\Carbon::today()->addDays(14)->format('Y-m-d H:i:s'),
                "folup_date"=> \Carbon\Carbon::today()->addDays(7)->format('Y-m-d H:i:s'),
                "no_quote"=> "RJO-XIII-2023-125",
                "termcon"=> "Validity Of Quotation: 1 Month since the Date of Quotation",
                "tax"=> "12",
                "shipping"=> "50000",
                "subtotal"=> "1500000",
                "harga_total"=> "1571000",
            ],
            [
                "id_client"=> "3",
                "id_sales"=> "2",
                "id_service"=> NULL,
                "status"=> "Done PO",
                "expired_date"=> \Carbon\Carbon::today()->addDays(14)->format('Y-m-d H:i:s'),
                "folup_date"=> \Carbon\Carbon::today()->addDays(7)->format('Y-m-d H:i:s'),
                "no_quote"=> "RJO-XIII-2023-123",
                "termcon"=> "Validity Of Quotation: 1 Year since the Date of Quotation",
                "tax"=> "12",
                "shipping"=> "500000",
                "subtotal"=> "50000000",
                "harga_total"=> "50600000",
            ],
            [
                "id_client"=> "3",
                "id_sales"=> "1",
                "id_service"=> NULL,
                "status"=> "Loss",
                "expired_date"=> \Carbon\Carbon::today()->addDays(28)->format('Y-m-d H:i:s'),
                "folup_date"=> \Carbon\Carbon::today()->addDays(21)->format('Y-m-d H:i:s'),
                "no_quote"=> "RJO-XIII-2023-124",
                "termcon"=> "Validity Of Quotation: 2 Month since the Date of Quotation",
                "tax"=> "15",
                "shipping"=> "500000",
                "subtotal"=> "200000000",
                "harga_total"=> "202500000",
            ],
        ];
        Quotation::insert($quotation);
    }
}
