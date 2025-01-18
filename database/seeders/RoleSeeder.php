<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Creacion de Roles
       $adminRole = Role::create(["name"=> "admin"]);
       $solicitanteRole = Role::create(["name"=> "solicitante"]);
       $aprobadorRole = Role::create(["name"=> "aprobador"]);
       $almacenistaRole = Role::create(["name"=> "almacenista"]);
       $adminCompraRole = Role::create(["name"=> "administrador_compra"]);
       


       //Permisos para el rol admin
       Permission::create(["name"=> "admin.home"])->assignRole($adminRole);
       Permission::create(["name"=> "admin.solicitudes"])->assignRole($adminRole);
       Permission::create(["name"=> "admin.delete"])->assignRole($adminRole);
       Permission::create(["name"=> "compras.edit"])->assignRole($adminRole);
       Permission::create(["name"=> "compras.delete"])->assignRole($adminRole);

       //Permisos generales
       Permission::create(["name"=> "productos.index"])->syncRoles([$solicitanteRole,$aprobadorRole, $almacenistaRole, $adminCompraRole, $adminRole]);
       Permission::create(["name"=> "productos.create"])->syncRoles([$solicitanteRole,$aprobadorRole, $almacenistaRole, $adminCompraRole]);

       //Permisos para el rol almacenista
       Permission::create(["name"=> "almacen.index"])->assignRole($almacenistaRole);
       Permission::create(["name"=> "almacen.update"])->assignRole(roles: $almacenistaRole);

       //Permisos para el rol aprobador
       Permission::create(["name"=> "aprobar.index"])->assignRole($aprobadorRole);
       Permission::create(["name"=> "aprobar.edit"])->assignRole($aprobadorRole);
    
       //Permisos para el rol admin de compras
       Permission::create(["name"=> "adminCompras.index"])->assignRole($adminCompraRole);
       Permission::create(["name"=> "adminCompras.edit"])->assignRole($adminCompraRole);
       

        //Permisos para solicitante
        Permission::create(["name"=> "solicitante.pendientes"])->assignRole($solicitanteRole);

       

    }
}
