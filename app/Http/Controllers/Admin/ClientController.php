<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityMember;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Schema::hasTable('community_members')
            ? CommunityMember::query()->orderBy('name')->get()
            : collect();

        return view('admin.clients', compact('clients'));
    }
}
