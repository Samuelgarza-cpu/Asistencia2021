<?php

use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status')->insert([
            'name' => 'Pendiente Anexo Archivos',
            'code' => 'PAA'
        ]);
        DB::table('status')->insert([
            'name' => 'Pendiente Autorización',
            'code' => 'PA'
        ]);
        DB::table('status')->insert([
            'name' => 'Entregada Pendiente Autorización',
            'code' => 'EPA'
        ]);
        DB::table('status')->insert([
            'name' => 'Autorizada Pendiente Entrega',
            'code' => 'APE'
        ]);
        DB::table('status')->insert([
            'name' => 'Rechazada',
            'code' => 'Re'
        ]);
        DB::table('status')->insert([
            'name' => 'Finalizada',
            'code' => 'Fi'
        ]);
        DB::table('status')->insert([
            'name' => 'Cancelada',
            'code' => 'Can'
        ]);
        DB::table('status')->insert([
            'name' => 'Inconclusa',
            'code' => 'Inco'
        ]);
        
    }
}
