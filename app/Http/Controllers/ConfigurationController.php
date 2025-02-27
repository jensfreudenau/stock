<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class ConfigurationController extends Controller
{
    public function index()
    {
        $configurations = Configuration::orderBy('name')->get();
        return view('configuration.index', compact('configurations'));
    }

    public function add(Request $request): Application|JsonResponse|Redirector|RedirectResponse
    {
        return redirect('/configuration/index');
    }

    public function store(Request $request): Application|Redirector|RedirectResponse
    {
       Configuration::create($request->all());
        return redirect('/configuration/index');
    }

    public function update(Request $request): Application|Redirector|RedirectResponse
    {
        $configuration = Configuration::find($request->id);
        $configuration->update($request->all());
        return redirect('/configuration/index');
    }

    public function destroy(Configuration $configuration): Application|Redirector|RedirectResponse
    {
        $configuration->delete();
        return redirect('configuration/index')->with('success', 'Configuration deleted successfully');
    }
}
