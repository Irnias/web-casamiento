<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected readonly EventRepository $eventRepository
    ) {}

    public function index(Event $event): Factory|View
    {
        $user = Auth::user();

        if (! $user->hasPermission(PermissionEnum::VIEW_DASHBOARD, $event)) {
            abort(403);
        }

        $owners = $this->eventRepository->getOwnersOptimized($event);

        $stats = [
            'guests_count' => $event->guests()->count(),
            'photos_pending' => $event->photos()->where('status', 'pending')->count(),
            'owners_names' => $owners->pluck('name')->join(', '),
        ];

        return view('dashboard.index', compact('event', 'stats'));
    }
}
