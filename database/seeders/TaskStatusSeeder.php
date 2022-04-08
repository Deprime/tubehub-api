<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{
  Hash, DB,
};

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('task_type')->truncate();
      DB::table('task_status')->insert([
        [ // 1
          'title_ru'  => 'Черновик',
          'title_en'  => 'Draft',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ], [ // 2
          'title_ru'  => 'Опубликовано',
          'title_en'  => 'Published',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ], [ // 3
          'title_ru'  => 'В работе',
          'title_en'  => 'In progress',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ], [ // 4
          'title_ru'  => 'В работе',
          'title_en'  => 'In progress',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ], [ // 5
          'title_ru'  => 'На проверке',
          'title_en'  => 'Verification',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ], [ // 6
          'title_ru'  => 'Ожижает оплаты',
          'title_en'  => 'Awaiting payment',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ], [ // 7
          'title_ru'  => 'Ожижает оплаты',
          'title_en'  => 'Awaiting payment',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ], [ // 8
          'title_ru'  => 'Спор',
          'title_en'  => 'Dispute',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ], [ // 9
          'title_ru'  => 'Отмененно',
          'title_en'  => 'Canceled',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ],
      ]);
    }
}
