<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;







class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            "name"=> "admin test",
            "email"=> "admin@gmail.com",
            "is_pending" => true,
            "password"=> bcrypt("12345678"),

        ])->assignRole('admin');

        User::create([
            "name"=> "jorge_levi",
            "email"=> "jorgeleviakerman@gmail.com",
            "is_pending" => false,
            "password"=> bcrypt("12345678"),

        ])->assignRole('solicitante');


        User::create([
            "name"=> "jorge_termacol",
            "email"=> "jorgerenteriatermacol@gmail.com",
            "is_pending" => false,
            "password"=> bcrypt("12345678"),

        ])->assignRole('aprobador');

        User::create([
            "name"=> "silla_tarro",
            "email"=> "sillatarrogogo@gmail.com",
            "is_pending" => false,
            "password"=> bcrypt("12345678"),

        ])->assignRole('administrador_compra');

        User::create([
            "name"=> "jorge_almacen",
            "email"=> "jorgerenteria1920@gmail.com",
            "is_pending" => false,
            "password"=> bcrypt("12345678"),

        ])->assignRole('almacenista');

        
    }
}
