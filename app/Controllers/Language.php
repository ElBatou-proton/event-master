<?php

namespace App\Controllers;

class Language extends BaseController
{
    public function switch(string $locale)
    {
        $supported = config('App')->supportedLocales;

        if (in_array($locale, $supported, true)) {
            session()->set('locale', $locale);
        }

        return redirect()->back();
    }
}
