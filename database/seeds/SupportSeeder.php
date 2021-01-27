<?php

use Illuminate\Database\Seeder;

class SupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('supports')->insert([
            'name' => 'Implementos ortopedicos',
            'code' => 'ImOr',
            'departments_institutes_id' => 4
        ]);
        DB::table('supports')->insert([
            'name' => 'General',
            'code' => 'Gene',
            'departments_institutes_id' => 4
        ]);
        DB::table('supports')->insert([
            'name' => 'Medicamentos',
            'code' => 'Medi',
            'departments_institutes_id' => 4
        ]);
        DB::table('supports')->insert([
            'name' => 'Funeraria',
            'code' => 'Fune',
            'departments_institutes_id' => 4
        ]);
        DB::table('supports')->insert([
            'name' => 'Estudios Medicos',
            'code' => 'EsMe',
            'departments_institutes_id' => 4
        ]);
        DB::table('supports')->insert([
            'name' => 'General hospitalario',
            'code' => 'ApOr',
            'departments_institutes_id' => 4
        ]);
        DB::table('supports')->insert([
            'name' => 'Lentes',
            'code' => 'ApOr',
            'departments_institutes_id' => 4
        ]);
        DB::table('supports')->insert([
            'name' => 'Despensas',
            'code' => 'Desp',
            'departments_institutes_id' => 4
        ]);
        DB::table('supports')->insert([
            'name' => 'Aparatos ortopedicos',
            'code' => 'Apar',
            'departments_institutes_id' => 4
        ]);
        DB::table('supports')->insert([
            'name' => 'Transporte',
            'code' => 'Tran',
            'departments_institutes_id' => 4
        ]);
        DB::table('supports')->insert([
            'name' => 'Viajes',
            'code' => 'Viaj',
            'departments_institutes_id' => 4
        ]);
    }
}
