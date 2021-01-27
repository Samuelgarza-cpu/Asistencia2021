<?php

use Illuminate\Database\Seeder;

class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('institutes')->insert([
            'name' => 'Presidencia',
            'code' => 'Presi'
        ]);
        DB::table('institutes')->insert([
            'name' => 'DIF',
            'code' => 'DIF'
        ]);
    }
}
