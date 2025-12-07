<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\View\View;

class PublicLicenseValidatorController extends Controller
{
    public function __invoke(License $license): View
    {
        return view('licenses.validator', [
            'license' => $license->loadMissing('product'),
        ]);
    }
}
