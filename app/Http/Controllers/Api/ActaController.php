<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Acta;
use Illuminate\Http\Request;

class ActaController extends Controller
{
    /**
     * Lista de actas para app móvil con todas las relaciones
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            
            $actas = Acta::with([
                'persona', 
                'persona2',
                'padrino1', 
                'madrina1',
                'padrino', 
                'madrina',
                'padre', 
                'madre',
                'padre1', 
                'madre1',
                'sacerdoteCelebrante', 
                'sacerdoteAsistente',
                'obispoCelebrante',
                'ermita',
                'tipoActa' // Relación con sacramento
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $actas->items(),
                'pagination' => [
                    'current_page' => $actas->currentPage(),
                    'last_page' => $actas->lastPage(),
                    'per_page' => $actas->perPage(),
                    'total' => $actas->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar actas: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Detalle de un acta específica con todas las relaciones
     */
    public function show($id)
    {
        try {
            $acta = Acta::with([
                'persona', 
                'persona2',
                'padrino1', 
                'madrina1',
                'padrino', 
                'madrina',
                'padre', 
                'madre',
                'padre1', 
                'madre1',
                'sacerdoteCelebrante', 
                'sacerdoteAsistente',
                'obispoCelebrante',
                'ermita',
                'tipoActa'
            ])->find($id);

            if (!$acta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acta no encontrada'
                ], 404);
            }

            // Agregar información adicional procesada
            $actaData = $acta->toArray();
            
            // Agregar el tipo de sacramento como alias para compatibilidad
            if ($acta->tipoActa) {
                $actaData['sacramento'] = $acta->tipoActa;
            }

            return response()->json([
                'success' => true,
                'data' => $actaData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener acta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Búsqueda de actas mejorada
     */
    public function search(Request $request)
    {
        try {
            $term = $request->get('q', $request->route('term', ''));
            
            if (empty($term)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Término de búsqueda requerido',
                    'data' => []
                ], 400);
            }

            $actas = Acta::with([
                'persona', 
                'persona2',
                'padrino1', 
                'madrina1',
                'padrino', 
                'madrina',
                'sacerdoteCelebrante',
                'ermita',
                'tipoActa'
            ])
            ->where(function($query) use ($term) {
                // Buscar en campos del acta
                $query->where('cve_actas', 'LIKE', "%{$term}%")
                      ->orWhere('numero_consecutivo', 'LIKE', "%{$term}%")
                      ->orWhere('Libro', 'LIKE', "%{$term}%")
                      ->orWhere('Folio', 'LIKE', "%{$term}%")
                      
                      // Buscar en persona principal
                      ->orWhereHas('persona', function($q) use ($term) {
                          $q->where('nombre', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_paterno', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_materno', 'LIKE', "%{$term}%")
                            ->orWhere('apellidos', 'LIKE', "%{$term}%");
                      })
                      
                      // Buscar en segunda persona
                      ->orWhereHas('persona2', function($q) use ($term) {
                          $q->where('nombre', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_paterno', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_materno', 'LIKE', "%{$term}%")
                            ->orWhere('apellidos', 'LIKE', "%{$term}%");
                      })
                      
                      // Buscar en padrinos/testigos
                      ->orWhereHas('padrino', function($q) use ($term) {
                          $q->where('nombre', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_paterno', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_materno', 'LIKE', "%{$term}%")
                            ->orWhere('apellidos', 'LIKE', "%{$term}%");
                      })
                      
                      ->orWhereHas('madrina', function($q) use ($term) {
                          $q->where('nombre', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_paterno', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_materno', 'LIKE', "%{$term}%")
                            ->orWhere('apellidos', 'LIKE', "%{$term}%");
                      })
                      
                      ->orWhereHas('padrino1', function($q) use ($term) {
                          $q->where('nombre', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_paterno', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_materno', 'LIKE', "%{$term}%")
                            ->orWhere('apellidos', 'LIKE', "%{$term}%");
                      })
                      
                      ->orWhereHas('madrina1', function($q) use ($term) {
                          $q->where('nombre', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_paterno', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_materno', 'LIKE', "%{$term}%")
                            ->orWhere('apellidos', 'LIKE', "%{$term}%");
                      })
                      
                      // Buscar en sacerdote
                      ->orWhereHas('sacerdoteCelebrante', function($q) use ($term) {
                          $q->where('nombre', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_paterno', 'LIKE', "%{$term}%")
                            ->orWhere('apellido_materno', 'LIKE', "%{$term}%")
                            ->orWhere('apellidos', 'LIKE', "%{$term}%");
                      })
                      
                      // Buscar en ermita
                      ->orWhereHas('ermita', function($q) use ($term) {
                          $q->where('nombre_ermita', 'LIKE', "%{$term}%");
                      })
                      
                      // Buscar en tipo de sacramento
                      ->orWhereHas('tipoActa', function($q) use ($term) {
                          $q->where('nombre', 'LIKE', "%{$term}%");
                      });
            })
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

            // Agregar alias para compatibilidad
            $actasData = $actas->map(function($acta) {
                $actaArray = $acta->toArray();
                if ($acta->tipoActa) {
                    $actaArray['sacramento'] = $acta->tipoActa;
                }
                return $actaArray;
            });

            return response()->json([
                'success' => true,
                'data' => $actasData,
                'total' => $actasData->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Obtener actas por tipo de sacramento
     */
    public function porTipo(Request $request, $tipoId)
    {
        try {
            $perPage = $request->get('per_page', 15);
            
            $actas = Acta::with([
                'persona', 
                'persona2',
                'padrino1', 
                'madrina1',
                'padrino', 
                'madrina',
                'sacerdoteCelebrante',
                'ermita',
                'tipoActa'
            ])
            ->where('tipo_acta', $tipoId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $actas->items(),
                'pagination' => [
                    'current_page' => $actas->currentPage(),
                    'last_page' => $actas->lastPage(),
                    'per_page' => $actas->perPage(),
                    'total' => $actas->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar actas por tipo: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de actas
     */
    public function estadisticas()
    {
        try {
            $estadisticas = [
                'total_actas' => Acta::count(),
                'por_tipo' => Acta::with('tipoActa')
                    ->selectRaw('tipo_acta, COUNT(*) as total')
                    ->groupBy('tipo_acta')
                    ->get()
                    ->map(function($item) {
                        return [
                            'tipo_id' => $item->tipo_acta,
                            'tipo_nombre' => $item->tipoActa ? $item->tipoActa->nombre : 'Sin tipo',
                            'total' => $item->total
                        ];
                    }),
                'ultimas_registradas' => Acta::orderBy('created_at', 'desc')->take(5)->count(),
                'por_ermita' => Acta::with('ermita')
                    ->selectRaw('cve_ermitas, COUNT(*) as total')
                    ->groupBy('cve_ermitas')
                    ->orderByDesc('total')
                    ->take(5)
                    ->get()
                    ->map(function($item) {
                        return [
                            'ermita_id' => $item->cve_ermitas,
                            'ermita_nombre' => $item->ermita ? $item->ermita->nombre_ermita : 'Sin ermita',
                            'total' => $item->total
                        ];
                    })
            ];

            return response()->json([
                'success' => true,
                'data' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }
}
