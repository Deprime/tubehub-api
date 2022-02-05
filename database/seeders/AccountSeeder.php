<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('account')->insert([
        [
          'ext_id'      => 321,
          'email'       => 'srvevenu01@gmail.com',
          'created_at'  => date("Y-m-d H:i:s"),
          'updated_at'  => date("Y-m-d H:i:s"),
        ],
      ]);
    }
}
