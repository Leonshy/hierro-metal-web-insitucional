<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Marcador de posición: el inicio real de Hierro Metal se construye en la Fase 4
 * (frontend), con los módulos de la Fase 3.
 */
class HomeController extends Controller
{
    public function index(): View
    {
        return view('home');
    }
}
