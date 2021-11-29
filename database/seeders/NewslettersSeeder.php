<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Newsletter;

class NewslettersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawNewsletter = [];
        for ($index = 0; $index < 1000; $index++) {
        	array_push($rawNewsletter, [
        		'id' => generateUuid(),
        		'title' => 'Title ' . ($index + 1),
        		'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vel risus ut sapien condimentum luctus. Proin et ex ex. Duis id tincidunt arcu. Donec id dolor ac turpis pretium sodales. Etiam commodo et orci eget interdum. Pellentesque mollis lectus mauris, et faucibus tellus vestibulum ac. Vestibulum feugiat luctus diam. Donec rutrum porta magna, ac mollis ex porttitor quis. Quisque vitae magna id urna iaculis ultrices. Aenean aliquam, felis non rutrum dignissim, nibh est mattis ipsum, euismod consequat metus sem eu nisl. Proin libero lectus, gravida ac urna vel, fermentum volutpat ex. Proin volutpat vehicula leo, non imperdiet ligula elementum non. Cras vel erat nibh. Donec et tincidunt nisl, quis dignissim magna. Praesent et massa consequat, posuere ipsum nec, eleifend dui. Sed a urna et ligula molestie sodales sit amet ac ante. Nullam vel fringilla felis, a feugiat lectus. Vestibulum suscipit scelerisque elit vel euismod. Cras vehicula dui urna, scelerisque bibendum ex venenatis sed. Nam ligula metus, auctor in convallis sit amet, pretium nec ipsum. Nulla feugiat volutpat nunc, tincidunt dictum orci porttitor in. Proin consectetur ac arcu at congue. Sed hendrerit nulla quis turpis volutpat viverra. Nunc suscipit orci quis metus cursus pulvinar. Vestibulum scelerisque dolor metus, vitae consectetur mi tristique non. Nunc ultrices odio sit amet viverra malesuada. Nam pellentesque eros malesuada augue pretium pretium. Fusce interdum orci quis elit facilisis, egestas eleifend augue rutrum. Donec et malesuada erat, in condimentum quam. Mauris odio nulla, malesuada vel bibendum a, feugiat eu nulla.',
        		'created_at' => carbon()->now(),
        		'updated_at' => carbon()->now(),
        	]);
        }
        Newsletter::insert($rawNewsletter);
    }
}
