<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentPackage;

class PaymentPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentPackage::create([
            'name' => 'Paquete Básico',
            'description' => 'Perfecto para uso personal o pequeñas parroquias',
            'credits_amount' => 50,
            'price' => 9.99,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 1,
            'features' => [
                '50 registros de actas',
                'Soporte por email',
                'Válido por 12 meses'
            ]
        ]);

        PaymentPackage::create([
            'name' => 'Paquete Estándar',
            'description' => 'Ideal para parroquias medianas con actividad regular',
            'credits_amount' => 150,
            'price' => 24.99,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 2,
            'features' => [
                '150 registros de actas',
                'Soporte prioritario',
                'Válido por 12 meses',
                'Descuento del 17%'
            ]
        ]);

        PaymentPackage::create([
            'name' => 'Paquete Premium',
            'description' => 'Para parroquias grandes con alta demanda de registros',
            'credits_amount' => 500,
            'price' => 69.99,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 3,
            'features' => [
                '500 registros de actas',
                'Soporte prioritario 24/7',
                'Válido por 12 meses',
                'Descuento del 30%',
                'Funciones exclusivas'
            ]
        ]);

        PaymentPackage::create([
            'name' => 'Paquete Empresarial',
            'description' => 'Para diócesis y organizaciones religiosas grandes',
            'credits_amount' => 1000,
            'price' => 119.99,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 4,
            'features' => [
                '1000 registros de actas',
                'Soporte dedicado',
                'Válido por 12 meses',
                'Descuento del 40%',
                'Funciones exclusivas',
                'Gestión multiusuario'
            ]
        ]);
    }
}
