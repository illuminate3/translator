<?php

use App\Locale;
use Illuminate\Database\Seeder;

class LocaleTableSeeder extends Seeder
{
    public function run()
    {
        $languages = ['en', 'sv', 'no'];

        foreach ($languages as $language)
        {
            Locale::create(compact('language'));
        }
    }
}
