<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Menggunakan factory untuk membuat 1000 data palsu
        User::factory(20000)->create();
    }
}
