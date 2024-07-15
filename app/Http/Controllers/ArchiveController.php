<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\DetailQuotation;
use App\Models\Quotation;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function archive_quotation()
    {
        return view('pages.sales.quotation.archive.index');
    }

    public function unarchive_quotation($id)
    {
        $quotation = Quotation::find($id);

        $quotation->level = '1';
        $delQuote = $quotation->save();

        if ($delQuote) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function delete_archive_quotation($id){
        $quotation = Quotation::find($id);
        $detailQuote = DetailQuotation::where('id_quotation', $id)->get();
        $contract = Contract::where('id_quotation', $id)->get();

        $delQuote = $quotation->delete();

        foreach ($detailQuote as $dQuote) {
            $delDetQuote = $dQuote->delete();
        }
        foreach ($contract as $contracts) {
            $delContract = $contracts->delete();
        }

        if ($delQuote || $delDetQuote || $delContract) {
            return 1;
        } else {
            return 0;
        }
    }
}
