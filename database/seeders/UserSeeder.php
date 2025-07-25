<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name' => 'Kaprodi',
            'username' => 'kaprodi',
            'email' => 'kaprodi@gmail.com',
            'password' => Hash::make('12345'),
            'level' => 'kaprodi']);

        User::create([
            'name' => 'baak',
            'username' => 'baak',
            'email' => 'baak@gmail.com',
            'password' => Hash::make('12345'),
            'level' => 'baak']);

        User::create([
            'name' => 'kerumahtanggan',
            'username' => 'kerumahtanggan',
            'email' => 'kerumahtanggan@gmail.com',
            'password' => Hash::make('12345'),
            'level' => 'kerumahtanggan']);
    }
}