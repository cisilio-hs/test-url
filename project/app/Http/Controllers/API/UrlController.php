<?php

namespace App\Http\Controllers\API;

use App\Url;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UrlController extends Controller
{
    public function index(Request $request)
    {   
        $urls = Url::withCount('clicks')->orderBy('created_at','desc')->paginate(10);

        return $urls;
    }
}
