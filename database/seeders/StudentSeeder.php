<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{
  Hash, DB,
};

class StudentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = \Faker\Factory::create();

    DB::table('student')->truncate();
    $student_list = [];

    for ($i = 1; $i <= 300; $i++) {
      $student_list[] = [
        'author_id'   => 1,
        'study_group_id' => rand(1, 5),
        'first_name'  => $faker->firstName,
        'last_name'   => $faker->lastName,
        'email'       => $faker->safeEmail,
        'address'     => $faker->address,
        'country'     => $faker->country,
        'created_at'  => date("Y-m-d H:i:s"),
        'updated_at'  => date("Y-m-d H:i:s"),
      ];
    }

    DB::table('student')->insert($student_list);
  }
}
