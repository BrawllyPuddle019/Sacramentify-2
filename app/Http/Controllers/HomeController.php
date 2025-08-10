<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Home;
use App\Models\Acta;
use App\Models\Persona;
use App\Models\Sacerdote;
use App\Models\Ermita;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Contadores principales
        $totalActas = Acta::count();
        $totalPersonas = Persona::count();
        $totalSacerdotes = Sacerdote::count();
        $totalErmitas = Ermita::count();
        $totalUsuarios = User::count();

        // Contadores por tipo de sacramento
        $bautizoCount = Acta::where('tipo_acta', 2)->count();
        $confirmacionCount = Acta::where('tipo_acta', 3)->count();
        $matrimonioCount = Acta::where('tipo_acta', 1)->count();

        // Estadísticas por mes (últimos 6 meses)
        $monthlyStats = $this->getMonthlyStats();

        // Estadísticas por año
        $yearlyStats = $this->getYearlyStats();

        // Top ermitas más activas
        $topErmitas = $this->getTopErmitas();

        // Actividad reciente (últimas 10 actas)
        $recentActas = Acta::with(['persona', 'tipoActa', 'ermita'])
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        // Estadísticas de crecimiento
        $thisMonth = Acta::whereMonth('fecha', Carbon::now()->month)
            ->whereYear('fecha', Carbon::now()->year)
            ->count();
        
        $lastMonth = Acta::whereMonth('fecha', Carbon::now()->subMonth()->month)
            ->whereYear('fecha', Carbon::now()->subMonth()->year)
            ->count();

        $growth = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;

        // Información de créditos del usuario
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $userCredits = $user->getCredits();

        return view('home', compact(
            'totalActas', 'totalPersonas', 'totalSacerdotes', 'totalErmitas', 'totalUsuarios',
            'bautizoCount', 'matrimonioCount', 'confirmacionCount',
            'monthlyStats', 'yearlyStats', 'topErmitas', 'recentActas',
            'thisMonth', 'lastMonth', 'growth', 'userCredits'
        ));
    }

    private function getMonthlyStats()
    {
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            $data[] = Acta::whereMonth('fecha', $date->month)
                         ->whereYear('fecha', $date->year)
                         ->count();
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    private function getYearlyStats()
    {
        return Acta::select(
            DB::raw('YEAR(fecha) as year'),
            DB::raw('COUNT(*) as total')
        )
        ->whereNotNull('fecha')
        ->groupBy('year')
        ->orderBy('year', 'desc')
        ->limit(5)
        ->get();
    }

    private function getTopErmitas()
    {
        return Acta::select('ermitas.nombre_ermita', DB::raw('COUNT(*) as total'))
            ->join('ermitas', 'actas.cve_ermitas', '=', 'ermitas.cve_ermitas')
            ->groupBy('ermitas.cve_ermitas', 'ermitas.nombre_ermita')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
    }

    public function loadCounters()
    {
        $bautizoCount = Acta::where('tipo_acta', 2)->count();
        $confirmacionCount = Acta::where('tipo_acta', 3)->count();
        $matrimonioCount = Acta::where('tipo_acta', 1)->count();

        return view('partials.counter_cards', compact('bautizoCount', 'matrimonioCount', 'confirmacionCount'));
    }
}

