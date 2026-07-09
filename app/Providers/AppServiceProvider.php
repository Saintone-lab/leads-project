<?php

namespace App\Providers;

use App\Models\PrDiscussionMention;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Inject $prMentions ke navbar agar notifikasi PR mention
        // muncul di semua halaman tanpa mengubah setiap controller.
        View::composer('layouts.sales.navbar', function ($view) {
            if (Auth::check()) {
                $prMentions = PrDiscussionMention::where('id_user_mention', Auth::id())
                    ->where('level', '0')
                    ->with(['discussion.pending'])
                    ->orderByDesc('created_at')
                    ->take(10)
                    ->get();
                $view->with('prMentions', $prMentions);
            }
        });

        // Inject $prCount ke sidebar agar badge "Purchase Request" (New Purchase)
        // muncul di semua halaman untuk role Admin/Accounting, bukan cuma di Dashboard.
        View::composer('components.dashboard.sidebar', function ($view) {
            if (Auth::check() && in_array(Auth::user()->role, ['Admin', 'Accounting'])) {
                $prCount = PurchaseRequest::where('status', '0')->count();
                $view->with('prCount', $prCount);
            }
        });
    }
}
