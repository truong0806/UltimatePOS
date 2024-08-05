<?php

namespace Database\Seeders;

use App\Construction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create sample constructions
        Construction::create([
            'name' => 'Building A',
            'description' => 'Construction of Building A',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'budget' => 5000000.00,
        ]);

        Construction::create([
            'name' => 'Road B',
            'description' => 'Construction of Road B',
            'start_date' => '2024-02-01',
            'end_date' => '2024-08-31',
            'budget' => 3000000.00,
        ]);

        Construction::create([
            'name' => 'Bridge C',
            'description' => 'Construction of Bridge C',
            'start_date' => '2024-03-01',
            'end_date' => '2024-10-31',
            'budget' => 7000000.00,
        ]);
    }
}
