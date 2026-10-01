<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Support\Encabezado;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('faq', [
            'encabezado' => Encabezado::de('preguntas-frecuentes'),
            'faqs' => Faq::query()->activos()->ordenados()->get(),
        ]);
    }
}
