<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{
  Hash, DB,
};

class TaskPeriodicitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('task_type')->truncate();
      DB::table('task_periodicity')->insert([
        [
          'title_ru'  => 'Разовое задание',
          'title_en'  => 'One-time task',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ], [
          'title_ru'  => 'По графику',
          'title_en'  => 'On schedule',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ],
      ]);
    }
}
