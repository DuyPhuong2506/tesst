<?php

use Illuminate\Database\Seeder;
use App\Models\CustomerTask;

class CustomerTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerTask::create([
            'name' => '挙式付箋',
            'description' => '追伸　尚ご多用中恐縮に存じますが\n　　　時　　分より結婚式を行います\nご列席賜りたく 10分前までに\nお越しくださいますようお願い申し上げます',
        ]);

        CustomerTask::create([
            'name' => '受付付箋',
            'description' => '誠に恐れ入りますが　私共の受付係を\nお願い致したく　当日　　 時　　分までに\nお越しくださいますようお願い申し上げます',
        ]);

        CustomerTask::create([
            'name' => 'タクシーご案内付箋',
            'description' => 'タクシーご利用のご案内\n \n尚当日は渋谷駅よりタクシーをご利用くださいませ\n【領収書】とこちらの【ご案内カード】を\n結婚式場のエントランスにおります係の者にお渡しください\nご精算をさせて頂きます\n \n《 渋谷駅 ⇒ アンジェパティオ間 》',
        ]);

        CustomerTask::create([
            'name' => '祝辞付箋',
            'description' => '誠に恐れ入りますが当日一言お言葉を賜りますよう\nお願い申し上げます',
        ]);

        CustomerTask::create([
            'name' => '乾杯付箋',
            'description' => '誠に恐れ入りますが当日乾杯のご発声を賜りますよう\nお願い申し上げます',
        ]);

        CustomerTask::create([
            'name' => '親族紹介付箋',
            'description' => '追伸　尚ご多用中恐縮に存じますが\n当日　　　時 　　分より親族紹介を行います\nご列席賜りたく 10分前までに\nお越しくださいますようお願い申し上げます',
        ]);
    }
}
