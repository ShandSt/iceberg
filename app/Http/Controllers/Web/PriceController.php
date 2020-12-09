<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PriceController extends Controller
{
    public function __invoke()
    {
        if (!Storage::disk('public')->has('price.pdf')) {
            return abort(404);
        }

        return redirect('/storage/price.pdf');
    }
}
