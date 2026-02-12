<?php

namespace App\Http\Controllers;

use App\Services\RequestService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected RequestService $requestService
    ) {}

    public function __invoke()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $stats = $this->requestService->getDashboardStats($user->id, $user->isAdmin());

        return view('dashboard', compact('stats'));
    }
}
