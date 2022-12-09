<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class IntegrationListController extends Controller
{
    public function __invoke(): InertiaResponse
    {
        $providers = Provider::query()
                        ->select('providers.id', 'providers.name', 'user_source_providers.label', 'user_source_providers.status')
                        ->leftJoin('user_source_providers', 'user_source_providers.provider_id', 'providers.id')
                ->get();

        return Inertia::render('Integrations/Index', [
            'providers' => $providers,
        ]);
    }
}
