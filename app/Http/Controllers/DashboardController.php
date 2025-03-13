<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard/Index', [
            'organizationsCount' => auth()->user()->account->organizations()->count(),
            'leadsCount' => auth()->user()->account->leads()->count(),
            'contactsCount' => auth()->user()->account->contacts()->count(),
            ]);
    }
}
