<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // doctor
            'manage doctor',
            'edit doctor',
            'delete doctor',
            'add doctor',
            'view doctor',
            // patient
            'manage patient',
            'create patient',
            'edit patient',
            'delete patient',
            'view patient',
            // invoices
            'manage invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',
            'view invoices',
            // appointments 
            'manage appointments',  
            'create appointments',
            'edit appointments',
            'delete appointments',
            'view appointments',
            // treatments
            'manage treatments',
            'create treatments',
            'edit treatments',
            'delete treatments',
            'view treatments',  
            // prescriptions
            'manage prescriptions',
            'create prescriptions',
            'edit prescriptions',
            'delete prescriptions',
            'view prescriptions',
            // medication
            'manage medication',    
            'create medication',
            'edit medication',  
            'delete medication',
            'view medication',
            // payments
            'manage payments',
            'create payments',
            'edit payments',
            'delete payments',
            'view payments',
            // rooms
            'manage rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',
            'view rooms',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }


        $admin = Role::create(['name' => 'admin',]);
        $doctor = Role::create(['name' => 'doctor',]);
        $patient = Role::create(['name' => 'patient',]);
        
        $admin->givePermissionTo($permissions);
        $doctor->givePermissionTo([
            'manage appointments',  
            'create appointments',
            'edit appointments',
            'delete appointments',
            'view appointments',
            'view doctor',
            'view patient',
        ]);
        $patient->givePermissionTo([
            'view doctor',
        ]);
    }
}
