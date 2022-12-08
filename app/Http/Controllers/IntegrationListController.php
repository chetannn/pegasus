<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class IntegrationListController extends Controller
{
    public function __invoke(): InertiaResponse
    {
        $providers = Provider::query()
                 ->select('id', 'name')
                ->unionAll(
                    DB::table('user_source_providers')
                        ->select('id', DB::raw('null as name'))
                )
                ->get();

        return Inertia::render('Integrations/Index', [
            'providers' => $providers,
        ]);
    }
}
