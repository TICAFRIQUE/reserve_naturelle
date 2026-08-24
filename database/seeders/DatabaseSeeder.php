<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * ==========================================
         * 1. USERS
         * ==========================================
         */

        User::factory()
            ->admin()
            ->create([
                'nom' => 'ADMIN',
                'prenom' => 'Principal',
                'email' => 'admin@reserve-naturelle.test',
            ]);

        User::factory()
            ->count(49)
            ->client()
            ->create();


        /*
         * ==========================================
         * 2. CATEGORIES
         * ==========================================
         */

        $this->call([
            CategorySeeder::class,
        ]);


        /*
         * ==========================================
         * 3. SOUS-CATEGORIES
         * ==========================================
         */

        $this->call([
            SousCategorySeeder::class,
        ]);


        /*
         * ==========================================
         * 4. PRODUITS
         * ==========================================
         */

        $this->call([
            ProductSeeder::class,
        ]);


        /*
         * ==========================================
         * 5. FOURNISSEURS
         * ==========================================
         */

        $this->call([
            FournisseurSeeder::class,
        ]);


        /*
         * ==========================================
         * 6. ZONES
         * ==========================================
         */

        $this->call([
            ZoneSeeder::class,
        ]);


        /*
         * ==========================================
         * 7. ACHATS
         * ==========================================
         *
         * 50 achats
         * 600 lignes
         * Mise à jour du stock + CMP
         */

        $this->call([
            AchatSeeder::class,
        ]);


        /*
         * ==========================================
         * 8. COMMANDES
         * ==========================================
         *
         * 150 commandes
         * 1500 lignes
         */

        $this->call([
            OrderSeeder::class,
        ]);
    }
}