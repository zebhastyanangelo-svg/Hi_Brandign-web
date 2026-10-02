<?php

namespace App\Http\Controllers;

use App\Models\CommunityMember;
use Illuminate\Support\Facades\Schema;

class CommunityController extends Controller
{
    public function index()
    {
        $members = Schema::hasTable('community_members')
            ? CommunityMember::query()->orderBy('name')->get()->map(fn (CommunityMember $member) => $member->toArray())
            : collect();

        if ($members->isEmpty()) {
            $members = collect([
                ['name' => 'Lucía Herrera', 'company' => 'Norte Studio', 'role' => 'Founder & Creative Director', 'category' => 'Consumer & Creative', 'initials' => 'LH', 'tone' => 'clay', 'email' => 'lucia@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=700&q=82'],
                ['name' => 'Mateo Ríos', 'company' => 'Forma Capital', 'role' => 'Managing Partner', 'category' => 'Fintech & VC', 'initials' => 'MR', 'tone' => 'olive', 'email' => 'mateo@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=700&q=82'],
                ['name' => 'Camila Duarte', 'company' => 'Nativa Labs', 'role' => 'Co-founder', 'category' => 'AI & Deeptech', 'initials' => 'CD', 'tone' => 'blue', 'email' => 'camila@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=700&q=82'],
                ['name' => 'Andrés Soler', 'company' => 'Casa Nómada', 'role' => 'Founder', 'category' => 'Consumer & Creative', 'initials' => 'AS', 'tone' => 'rose', 'email' => 'andres@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=700&q=82'],
                ['name' => 'Valentina Cruz', 'company' => 'Lumen Health', 'role' => 'CEO', 'category' => 'AI & Deeptech', 'initials' => 'VC', 'tone' => 'olive', 'email' => 'valentina@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=700&q=82'],
                ['name' => 'Diego Montes', 'company' => 'Marea Ventures', 'role' => 'Investor', 'category' => 'Fintech & VC', 'initials' => 'DM', 'tone' => 'clay', 'email' => 'diego@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=700&q=82'],
            ]);
        }

        return view('community.index', compact('members'));
    }
}
