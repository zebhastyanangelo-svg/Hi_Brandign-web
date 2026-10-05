<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events' => Event::count(),
            'published_events' => Event::where('is_published', true)->count(),
            'upcoming_events' => Event::where('starts_at', '>', now())->where('is_published', true)->count(),
            'pending_bookings' => Booking::where('status', 'pendiente')->count(),
            'approved_bookings' => Booking::where('status', 'aprobado')->count(),
            'total_attendees' => Booking::where('status', 'aprobado')->sum('tickets_count'),
        ];

        $recentBookings = Booking::with('event')->latest()->take(5)->get();
        $upcomingEvents = Event::where('is_published', true)->where('starts_at', '>', now())->orderBy('starts_at')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'upcomingEvents'));
    }
}