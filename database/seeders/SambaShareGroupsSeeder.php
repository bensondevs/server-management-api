<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Container, SsambaShare, SambaGroup, SambaShareGroup };

class SambaShareGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $containers = Container::with(['sambaShares', 'sambaGroups'])->get();
        
        $rawShareGroups = [];
        foreach ($containers as $container) {
            $shares = $container->sambaShares;
            $groups = $container->sambaGroups;

            foreach ($shares as $share) {
                foreach ($groups as $group) {
                    array_push($rawShareGroups, [
                        'id' => generateUuid(),
                        'container_id' => $container->id,
                        'samba_group_id' => $group->id,
                        'samba_share_id' => $share->id,
                    ]);
                }
            }
        }

        foreach (array_chunk($rawShareGroups, 50) as $chunk) {
            SambaShareGroup::insert($chunk);
        }
    }
}
