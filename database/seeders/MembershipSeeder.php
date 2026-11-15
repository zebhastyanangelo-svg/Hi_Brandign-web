<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $slugs = [];

        foreach (Membership::defaultCatalog() as $plan) {
            $membership = Membership::query()
                ->where('slug', $plan['slug'])
                ->orWhere('sort_order', $plan['sort_order'])
                ->first()
                ?? new Membership;

            $membership->fill($plan)->save();

            $slugs[] = $plan['slug'];
        }

        Membership::query()
            ->whereIn('slug', ['daylight', 'studio', 'house'])
            ->whereNotIn('slug', $slugs)
            ->delete();
    }
}
