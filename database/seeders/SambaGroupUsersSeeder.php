<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Container, SambaGroupUser };

class SambaGroupUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $containers = Container::with(['sambaGroups', 'sambaUsers'])->get();

        $rawGroupUsers = array();
        foreach ($containers as $container) {
            $groups = $container->sambaGroups;
            $users = $container->sambaUsers;
            foreach ($groups as $group) {
                foreach ($users->random(2) as $user) {
                    array_push($rawGroupUsers, [
                        'id' => generateUuid(),
                        'container_id' => $container->id,
                        'samba_group_id' => $group->id,
                        'samba_group_user_id' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        SambaGroupUser::insert($rawGroupUsers);
    }
}
