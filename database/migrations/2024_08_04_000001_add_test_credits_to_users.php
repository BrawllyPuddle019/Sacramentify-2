<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\UserCredit;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar créditos a todos los usuarios existentes para testing
        $users = User::all();
        
        foreach ($users as $user) {
            // Buscar si ya tiene créditos
            $userCredit = UserCredit::where('user_id', $user->id)->first();
            
            if (!$userCredit) {
                // Crear registro de créditos si no existe
                UserCredit::create([
                    'user_id' => $user->id,
                    'available_credits' => 50, // 50 créditos gratis
                    'used_credits' => 0,
                    'total_credits' => 0
                ]);
            } else {
                // Agregar créditos si ya existe el registro
                $userCredit->available_credits += 50;
                $userCredit->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacer nada en rollback
    }
};
