<?php

namespace App\Http\Traits;


trait WalletSuccess
{
    function flashSuccessPage($title, $brief, $detail)
    {
        session()->flash('success_title', $title);
        session()->flash('success_brief', $brief);
        session()->flash('success_detail', $detail);
    }
}
