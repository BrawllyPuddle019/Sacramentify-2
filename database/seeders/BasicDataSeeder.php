<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar sacramentos básicos
        DB::table('sacramentos')->insert([
            ['cve_sacramentos' => 1, 'nombre_sacramento' => 'Matrimonio', 'descripcion' => 'Sacramento del Matrimonio'],
            ['cve_sacramentos' => 2, 'nombre_sacramento' => 'Bautismo', 'descripcion' => 'Sacramento del Bautismo'],
            ['cve_sacramentos' => 3, 'nombre_sacramento' => 'Confirmación', 'descripcion' => 'Sacramento de la Confirmación']
        ]);

        // Insertar estados básicos
        DB::table('estados')->insert([
            ['cve_estado' => 1, 'nombre_estado' => 'Aguascalientes'],
            ['cve_estado' => 2, 'nombre_estado' => 'Baja California'],
            ['cve_estado' => 3, 'nombre_estado' => 'Chihuahua']
        ]);

        // Insertar municipios básicos
        DB::table('municipios')->insert([
            ['cve_municipio' => 1, 'nombre_municipio' => 'Aguascalientes', 'cve_estado' => 1],
            ['cve_municipio' => 2, 'nombre_municipio' => 'Tijuana', 'cve_estado' => 2],
            ['cve_municipio' => 3, 'nombre_municipio' => 'Chihuahua', 'cve_estado' => 3]
        ]);

        // Insertar diócesis básica
        DB::table('diocesis')->insert([
            ['cve_diocesis' => 1, 'nombre_diocesis' => 'Diócesis de Aguascalientes', 'direccion_diocesis' => 'Centro de Aguascalientes']
        ]);

        // Insertar parroquia básica
        DB::table('parroquias')->insert([
            ['cve_parroquia' => 1, 'nombre_parroquia' => 'Parroquia San José', 'direccion' => 'Centro, Aguascalientes', 'cve_municipio' => 1, 'cve_diocesis' => 1]
        ]);

        // Insertar ermita básica
        DB::table('ermitas')->insert([
            ['cve_ermitas' => 1, 'nombre_ermita' => 'Ermita del Divino Salvador', 'direccion' => 'Centro, Aguascalientes', 'cve_municipio' => 1, 'cve_parroquia' => 1]
        ]);

        // Insertar obispo básico
        DB::table('obispos')->insert([
            ['cve_obispos' => 1, 'nombre_obispo' => 'José', 'apellido_paterno' => 'García', 'apellido_materno' => 'López', 'cve_diocesis' => 1]
        ]);

        // Insertar sacerdote básico
        DB::table('sacerdotes')->insert([
            ['cve_sacerdotes' => 1, 'nombre_sacerdote' => 'Miguel', 'apellido_paterno' => 'Pérez', 'apellido_materno' => 'Hernández', 'cve_diocesis' => 1]
        ]);

        // Insertar personas básicas
        DB::table('personas')->insert([
            ['cve_persona' => 1, 'nombre' => 'Juan', 'apellido_paterno' => 'López', 'apellido_materno' => 'García', 'fecha_nacimiento' => '1990-01-15', 'sexo' => 'M', 'cve_municipio' => 1],
            ['cve_persona' => 2, 'nombre' => 'María', 'apellido_paterno' => 'Hernández', 'apellido_materno' => 'Ruiz', 'fecha_nacimiento' => '1992-03-20', 'sexo' => 'F', 'cve_municipio' => 1],
            ['cve_persona' => 3, 'nombre' => 'Pedro', 'apellido_paterno' => 'Martínez', 'apellido_materno' => 'Silva', 'fecha_nacimiento' => '1985-07-10', 'sexo' => 'M', 'cve_municipio' => 1],
            ['cve_persona' => 4, 'nombre' => 'Ana', 'apellido_paterno' => 'González', 'apellido_materno' => 'Torres', 'fecha_nacimiento' => '1988-11-05', 'sexo' => 'F', 'cve_municipio' => 1]
        ]);

        // Insertar acta de ejemplo
        DB::table('actas')->insert([
            [
                'cve_actas' => 1,
                'numero_consecutivo' => 1,
                'cve_persona' => 1,
                'cve_persona2' => 2,
                'cve_ermitas' => 1,
                'cve_sacerdotes_celebrante' => 1,
                'cve_obispos_celebrante' => 1,
                'fecha' => '2025-08-01 10:00:00',
                'Libro' => '1',
                'Fojas' => 1,
                'Folio' => 1,
                'tipo_acta' => 1
            ]
        ]);

        echo "Datos básicos insertados correctamente\n";
    }
}
