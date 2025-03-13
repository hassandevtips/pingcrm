<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Traits\Status;
use App\Models\Lead;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class LeadsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Leads/Index', [
            'filters' => Request::all('search', 'trashed'),
            'leads' => Auth::user()->account->leads()
                ->with('organization')
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($lead) => [
                    'id' => $lead->id,
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'status' => $lead->status,
                    'deleted_at' => $lead->deleted_at,
                    'organization' => $lead->organization ? $lead->organization->only('name') : null,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Leads/Create', [
            'organizations' => Auth::user()->account
                ->organizations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
            'contacts' => Auth::user()->account
                ->contacts()
                ->get(),
            'statuses' => Status::statuses(),
        ]);
    }

    public function store(): RedirectResponse
    {
        Auth::user()->account->leads()->create(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organization_id' => ['nullable', Rule::exists('organizations', 'id')->where(function ($query) {
                    $query->where('account_id', Auth::user()->account_id);
                })],
                'contact_id' => ['required', Rule::exists('contacts', 'id')->where(function ($query) {
                    $query->where('account_id', Auth::user()->account_id);
                })],
                'email' => ['required', 'max:50', 'email', 'unique:leads,email'],
                'phone' => ['nullable', 'max:50'],
                'description' => ['nullable', 'max:150'],
                'status' => ['required'],
            ])
        );

        return Redirect::route('leads')->with('success', 'Hey Look Lead created.');
    }

    public function edit($id): Response
    {
        $lead = Lead::findOrFail($id);
        return Inertia::render('Leads/Edit', [
            'lead' => [
                'id' => $lead->id,
                'first_name' => $lead->first_name,
                'last_name' => $lead->last_name,
                'organization_id' => $lead->organization_id,
                'contact_id' => $lead->contact_id,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'description' => $lead->description,
                'status' => $lead->status,
                'deleted_at' => $lead->deleted_at,
            ],
            'organizations' => Auth::user()->account->organizations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
            'contacts' => Auth::user()->account
                ->contacts()
                ->get(),
            'statuses' => Status::statuses(),
        ]);
    }

    public function update(Lead $lead): RedirectResponse
    {
        $lead->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organization_id' => [
                    'nullable',
                    Rule::exists('organizations', 'id')->where(fn ($query) => $query->where('account_id', Auth::user()->account_id)),
                ],
                'contact_id' => ['required', Rule::exists('contacts', 'id')->where(function ($query) {
                    $query->where('account_id', Auth::user()->account_id);
                })],
                'email' => [
                    'required',
                    'max:50',
                    'email',
                    //Rule::unique('leads', 'email')->ignore($lead->id),
                ],

                'phone' => ['nullable', 'max:50'],
                'description' => ['nullable', 'max:150'],
                'status' => ['required'],
            ])
        );

        return Redirect::back()->with('success', 'Lead updated.');
    }

    public function destroy($id): RedirectResponse
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return Redirect::route('leads')->with('success', 'Lead deleted.');
    }

    public function restore($id): RedirectResponse
    {
        $lead = Lead::findOrFail($id);
        $lead->restore();

        return Redirect::route('leads')->with('success', 'Lead restored.');
    }
}
