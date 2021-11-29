<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Container, SambaShareUser };

class SambaShareUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $containers = Container::with(['sambaShares', 'sambaUsers'])->get();

        $rawShareUsers = [];
        foreach ($containers as $container) {
            $shares = $container->sambaShares;
            $users = $container->sambaUsers;

            foreach ($shares as $share) {
                foreach ($users as $user) {
                    array_push($rawShareUsers, [
                        'id' => generateUuid(),
                        'container_id' => $container->id,
                        'samba_share_id' => $share->id,
                        'samba_user_id' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        foreach (array_chunk($rawShareUsers, 500) as $chunk) {
            SambaShareUser::insert($chunk);
        }
    }
}
