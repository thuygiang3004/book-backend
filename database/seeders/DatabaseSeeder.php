<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\BooksExportConfig;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Create superAdmin role
        $role = Role::create(['name' => 'super-admin']);
        $permission = Permission::create(['name' => 'edit books']);
        $role->givePermissionTo($permission);

        //Create super admin user
        $superAdmin = User::factory()->create(['email' => 'giang2@gmail.com']);
        $superAdmin->assignRole('super-admin');

        User::factory(10)->create();
        Book::factory(20)->has(Listing::factory(2)->hasComments(2))->hasComments(3)->create();
        BooksExportConfig::factory()->create();
    }
}
