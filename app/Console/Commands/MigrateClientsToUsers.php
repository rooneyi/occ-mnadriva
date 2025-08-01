<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateClientsToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-clients-to-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Charger les modèles
        \App\Models\Client::unguard();
        \App\Models\User::unguard();

        $clients = \App\Models\Client::all();
        $migrated = 0;
        $skipped = 0;
        foreach ($clients as $client) {
            // Vérifier si l'email existe déjà dans users
            if (\App\Models\User::where('email', $client->email)->exists()) {
                $this->warn("Email déjà présent dans users: {$client->email}, client id: {$client->id_client}");
                $skipped++;
                continue;
            }
            // Créer le nouvel utilisateur
            \App\Models\User::create([
                'name' => '',
                'email' => $client->email,
                'password' => $client->password, // déjà hashé
                'role' => 'client',
                'created_at' => $client->created_at,
                'updated_at' => $client->updated_at,
                'remember_token' => $client->remember_token ?? null,
            ]);
            $this->info("Client id {$client->id_client} migré vers users.");
            $migrated++;
        }
        $this->info("Migration terminée. $migrated clients migrés, $skipped ignorés (email déjà utilisé).");
    }
}
