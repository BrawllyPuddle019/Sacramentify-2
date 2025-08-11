<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acta;
use App\Models\Sacramento;
use Illuminate\Support\Facades\Log;

class ActaController extends Controller
{
    /**
     * Listar todas las actas con paginación para móvil
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);

            $actas = Acta::with([
                'persona', 'persona2',
                'padrino1', 'madrina1',
                'padrino', 'madrina', 
                'padre', 'madre', 'padre1', 'madre1',
                'ermita',
                'sacerdoteCelebrante', 'sacerdoteAsistente',
                'obispoCelebrante',
                'tipoActa'
            ])
            ->orderBy('fecha', 'desc')
            ->orderBy('numero_consecutivo', 'desc')
            ->paginate($perPage);

            $data = $actas->map(function($acta) {
                return $this->formatActaForMobile($acta);
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'current_page' => $actas->currentPage(),
                    'total_pages' => $actas->lastPage(),
                    'per_page' => $actas->perPage(),
                    'total' => $actas->total(),
                    'has_more' => $actas->hasMorePages()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener actas para móvil: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las actas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener una acta específica con todos sus detalles
     */
    public function show($id)
    {
        try {
            $acta = Acta::with([
                'persona', 'persona.municipios', 'persona.municipios.estado',
                'persona2', 'persona2.municipios', 'persona2.municipios.estado',
                'padrino1', 'padrino1.municipios', 'padrino1.municipios.estado',
                'madrina1', 'madrina1.municipios', 'madrina1.municipios.estado',
                'padrino', 'padrino.municipios', 'padrino.municipios.estado',
                'madrina', 'madrina.municipios', 'madrina.municipios.estado',
                'padre', 'padre.municipios', 'padre.municipios.estado',
                'madre', 'madre.municipios', 'madre.municipios.estado',
                'padre1', 'padre1.municipios', 'padre1.municipios.estado',
                'madre1', 'madre1.municipios', 'madre1.municipios.estado',
                'ermita',
                'sacerdoteCelebrante', 'sacerdoteAsistente',
                'obispoCelebrante',
                'tipoActa'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $this->formatActaForMobile($acta, true)
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener acta específica: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el acta',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Buscar actas por término
     */
    public function search(Request $request, $term = null)
    {
        try {
            // El término puede venir como parámetro de URL o query parameter
            $searchTerm = $term ?? $request->get('q', '');
            
            if (empty($searchTerm)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Término de búsqueda vacío'
                ]);
            }

            $actas = Acta::with([
                'persona', 'persona2',
                'padrino1', 'madrina1',
                'padrino', 'madrina', 
                'padre', 'madre', 'padre1', 'madre1',
                'ermita',
                'sacerdoteCelebrante', 'sacerdoteAsistente',
                'obispoCelebrante',
                'tipoActa'
            ])
            ->where(function($query) use ($searchTerm) {
                // Buscar en campos de acta
                $query->where('Libro', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('Folio', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('Fojas', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('numero_consecutivo', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('fecha', 'LIKE', "%{$searchTerm}%");
                
                // Buscar en persona principal
                $query->orWhereHas('persona', function($q) use ($searchTerm) {
                    $q->where('nombre', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('apellido_paterno', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('apellido_materno', 'LIKE', "%{$searchTerm}%");
                });
                
                // Buscar en persona secundaria (matrimonios)
                $query->orWhereHas('persona2', function($q) use ($searchTerm) {
                    $q->where('nombre', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('apellido_paterno', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('apellido_materno', 'LIKE', "%{$searchTerm}%");
                });

                // Buscar en ermita
                $query->orWhereHas('ermita', function($q) use ($searchTerm) {
                    $q->where('nombre', 'LIKE', "%{$searchTerm}%");
                });

                // Buscar en tipo de acta
                $query->orWhereHas('tipoActa', function($q) use ($searchTerm) {
                    $q->where('nombre', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('nombre_sacramento', 'LIKE', "%{$searchTerm}%");
                });
            })
            ->orderBy('fecha', 'desc')
            ->limit(20)
            ->get();

            $data = $actas->map(function($acta) {
                return $this->formatActaForMobile($acta);
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'total' => $actas->count(),
                'search_term' => $searchTerm
            ]);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de actas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filtrar actas por tipo de sacramento
     */
    public function porTipo($tipoId)
    {
        try {
            $actas = Acta::with([
                'persona', 'persona2',
                'padrino1', 'madrina1',
                'padrino', 'madrina', 
                'padre', 'madre', 'padre1', 'madre1',
                'ermita',
                'sacerdoteCelebrante', 'sacerdoteAsistente',
                'obispoCelebrante',
                'tipoActa'
            ])
            ->where('tipo_acta', $tipoId)
            ->orderBy('fecha', 'desc')
            ->paginate(15);

            $data = $actas->map(function($acta) {
                return $this->formatActaForMobile($acta);
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'current_page' => $actas->currentPage(),
                    'total_pages' => $actas->lastPage(),
                    'per_page' => $actas->perPage(),
                    'total' => $actas->total(),
                    'has_more' => $actas->hasMorePages()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al filtrar actas por tipo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al filtrar actas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas generales
     */
    public function estadisticas()
    {
        try {
            $totalActas = Acta::count();
            $totalBautizos = Acta::whereHas('tipoActa', function($q) {
                $q->where('nombre', 'LIKE', '%bautis%');
            })->count();
            
            $totalConfirmaciones = Acta::whereHas('tipoActa', function($q) {
                $q->where('nombre', 'LIKE', '%confirmac%');
            })->count();
            
            $totalMatrimonios = Acta::whereHas('tipoActa', function($q) {
                $q->where('nombre', 'LIKE', '%matrimonio%');
            })->count();

            $actasRecientes = Acta::with(['persona', 'tipoActa'])
                ->orderBy('fecha', 'desc')
                ->limit(5)
                ->get()
                ->map(function($acta) {
                    return [
                        'id' => $acta->cve_actas,
                        'tipo' => $acta->tipoActa->nombre ?? 'Sin tipo',
                        'persona' => $acta->persona ? 
                            $acta->persona->nombre . ' ' . $acta->persona->apellido_paterno : 
                            'Sin persona',
                        'fecha' => $acta->fecha
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'totales' => [
                        'total_actas' => $totalActas,
                        'bautizos' => $totalBautizos,
                        'confirmaciones' => $totalConfirmaciones,
                        'matrimonios' => $totalMatrimonios
                    ],
                    'actas_recientes' => $actasRecientes
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Formatear acta para respuesta móvil
     */
    private function formatActaForMobile($acta, $includeDetails = false)
    {
        // Información básica
        $formatted = [
            'id' => $acta->cve_actas,
            'numero_consecutivo' => $acta->numero_consecutivo,
            'fecha' => $acta->fecha,
            'libro' => $acta->Libro,
            'fojas' => $acta->Fojas,
            'folio' => $acta->Folio,
            
            // Tipo de acta/sacramento
            'tipoActa' => [
                'id' => $acta->tipoActa->cve_sacramentos ?? null,
                'nombre' => $acta->tipoActa->nombre ?? 'Sin sacramento',
                'nombre_sacramento' => $acta->tipoActa->nombre_sacramento ?? $acta->tipoActa->nombre ?? 'Sin sacramento'
            ],

            // Ermita
            'ermita' => $acta->ermita ? [
                'id' => $acta->ermita->cve_ermitas,
                'nombre' => $acta->ermita->nombre
            ] : null,

            // Sacerdotes
            'sacerdote_celebrante' => $acta->sacerdoteCelebrante ? [
                'id' => $acta->sacerdoteCelebrante->cve_sacerdotes,
                'nombre' => $acta->sacerdoteCelebrante->nombre
            ] : null,

            'sacerdote_asistente' => $acta->sacerdoteAsistente ? [
                'id' => $acta->sacerdoteAsistente->cve_sacerdotes,
                'nombre' => $acta->sacerdoteAsistente->nombre
            ] : null,

            // Obispo
            'obispo_celebrante' => $acta->obispoCelebrante ? [
                'id' => $acta->obispoCelebrante->cve_obispos,
                'nombre' => $acta->obispoCelebrante->nombre
            ] : null,

            // Personas principales
            'persona_principal' => $acta->persona ? $this->formatPersona($acta->persona, $includeDetails) : null,
            'persona_secundaria' => $acta->persona2 ? $this->formatPersona($acta->persona2, $includeDetails) : null,
        ];

        // Incluir detalles adicionales si se solicitan
        if ($includeDetails) {
            $formatted['padres_padrinos'] = [
                'padre' => $acta->padre ? $this->formatPersona($acta->padre, false) : null,
                'madre' => $acta->madre ? $this->formatPersona($acta->madre, false) : null,
                'padre1' => $acta->padre1 ? $this->formatPersona($acta->padre1, false) : null,
                'madre1' => $acta->madre1 ? $this->formatPersona($acta->madre1, false) : null,
                'padrino' => $acta->padrino ? $this->formatPersona($acta->padrino, false) : null,
                'madrina' => $acta->madrina ? $this->formatPersona($acta->madrina, false) : null,
                'padrino1' => $acta->padrino1 ? $this->formatPersona($acta->padrino1, false) : null,
                'madrina1' => $acta->madrina1 ? $this->formatPersona($acta->madrina1, false) : null,
            ];
        }

        return $formatted;
    }

    /**
     * Formatear información de persona
     */
    private function formatPersona($persona, $includeDetails = false)
    {
        if (!$persona) return null;

        $formatted = [
            'id' => $persona->cve_persona,
            'nombre_completo' => trim($persona->nombre . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno),
            'nombre' => $persona->nombre,
            'apellido_paterno' => $persona->apellido_paterno,
            'apellido_materno' => $persona->apellido_materno,
        ];

        if ($includeDetails) {
            $formatted = array_merge($formatted, [
                'fecha_nacimiento' => $persona->fecha_nacimiento ?? null,
                'sexo' => $persona->sexo,
                'lugar_nacimiento' => $persona->lugar_nacimiento ?? null,
                'municipio' => $persona->municipios ? [
                    'id' => $persona->municipios->cve_municipios,
                    'nombre' => $persona->municipios->nombre,
                    'estado' => $persona->municipios->estado ? [
                        'id' => $persona->municipios->estado->cve_estados,
                        'nombre' => $persona->municipios->estado->nombre
                    ] : null
                ] : null
            ]);
        }

        return $formatted;
    }
}
