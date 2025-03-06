<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\SavingsPlan;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class SavingsPlanController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::where('share_type', 'etf')->active()->pluck('name_short', 'id');
        $savingsPlans = SavingsPlan::orderBy('portfolio_id')->with('portfolio')->get();
        return view('savings_plan.index', compact('savingsPlans', 'portfolios'));
    }

    public function add(): Application|Redirector|RedirectResponse
    {
        return redirect('/savingsplan/index');
    }

    public function store(Request $request): Application|Redirector|RedirectResponse
    {
        request()->validate([
            'buy_at' => ['integer', 'min:1'],
            'quantity' => ['integer', 'min:1'],
        ]);
        SavingsPlan::create($request->all());
        return redirect('/savingsplan/index');
    }

    public function update(Request $request): Application|Redirector|RedirectResponse
    {
        request()->validate([
            'buy_at' => ['integer', 'min:1'],
            'quantity' => ['integer', 'min:1'],
        ]);
        $savingsPlan = SavingsPlan::find($request->id);
        $savingsPlan->update($request->all());
        return redirect('/savingsplan/index');
    }

    public function destroy(SavingsPlan $savingsPlan): Application|Redirector|RedirectResponse
    {
        $savingsPlan->delete();
        return redirect('savingsplan/index')->with('success', 'Savingsplan deleted successfully');
    }
}
