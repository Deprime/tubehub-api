<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{
  Hash, DB,
};

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('task_type')->truncate();
        DB::table('task_type')->insert([
        [
          'title_ru'  => 'Прибыть по адресу',
          'title_en'  => 'Arrive at address',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ], [
          'title_ru'  => 'Выполнить удаленно',
          'title_en'  => 'Execute remotely',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ],
      ]);
    }
}
