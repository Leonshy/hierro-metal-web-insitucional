<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Support\Encabezado;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        abort_unless(Page::visible('preguntas-frecuentes'), 404);

        return view('faq', [
            'encabezado' => Encabezado::de('preguntas-frecuentes'),
            'faqs' => Faq::query()->activos()->ordenados()->get(),
        ]);
    }
}
