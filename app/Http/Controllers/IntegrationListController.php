<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class IntegrationListController extends Controller
{
    public function __invoke(): InertiaResponse
    {
        return Inertia::render('Integrations/Index', [
        ]);
    }
}
