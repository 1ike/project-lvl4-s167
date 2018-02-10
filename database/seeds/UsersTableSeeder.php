<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         factory(App\User::class, 10)->create();


/*         function seed($faker, $counter)
        {
            if ($counter <= 0) {
                return;
            }

            $user = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'remember_token' => str_random(10),
            ];

            App\User::create($user);

            return seed($faker, $counter - 1);
        }

        $faker = Faker\Factory::create();
        seed($faker, 25); */
    }
}
