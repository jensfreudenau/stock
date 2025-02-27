<?php

namespace App\Http\Controllers;

use App\Models\StopLoss;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class StopLossController extends Controller
{
    public function index(): View|Factory|Application
    {
        $stopLosses = StopLoss::orderBy('portfolio_id')->get();
        return view('stop_loss.index', compact('stopLosses'));
    }

    public function add(Request $request): Application|Redirector|RedirectResponse
    {
        return redirect('/stoploss/index');
    }

    public function store(Request $request): Application|Redirector|RedirectResponse
    {
        StopLoss::create($request->all());
        return redirect('/stoploss/index');
    }

    public function update(Request $request): Application|Redirector|RedirectResponse
    {
        $stopLoss = StopLoss::find($request->id);
        $stopLoss->update($request->all());
        return redirect('/stoploss/index');
    }

    public function destroy(StopLoss $stopLoss): Application|Redirector|RedirectResponse
    {
        $stopLoss->delete();
        return redirect('stoploss/index')->with('success', 'StopLoss deleted successfully');
    }
}
