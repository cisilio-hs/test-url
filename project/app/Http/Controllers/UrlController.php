<?php

namespace App\Http\Controllers;

use App\Url;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class UrlController extends Controller
{
    public function index(Request $request)
    {   
        $urls = Url::withCount('clicks')->orderBy('created_at','desc')->take(10)->get();
        $url = new Url;

        return view('user.index', ['url' =>$url, 'urls' => $urls]);
    }

    public function store(Request $request)
    {
        $valid_data = $request->validate([
            'original_url' => 'required|url',
        ]);
        
        $valid_data['short_url'] = $this->generateShortUrl();
        
        $url = Url::create($valid_data);
        
        return redirect()->back()->with('success', 'Url created successfully!');
        /* Create a new URL record */
    }

    public function visit(String $url)
    {
        $url = Url::where('short_url', $url)->firstOrFail();
            
        $agent = new Agent();
        
        $url->clicks()->create([
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
        ]);
        
        return redirect($url->original_url);
    }


    public function show(String $url)
    {
        $url = Url::where('short_url', $url)->firstOrFail();
        
        $daily_clicks = $url->clicks()->select(
            \DB::raw('count(id) as total'),
            \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as day_order'),
            \DB::raw('DATE_FORMAT(created_at, "%D %M %Y") as day')
        )
            ->groupBy('day_order','day')
            ->orderBy('day_order','asc')
            ->get()
            ->map(
            function ($item)
            {
                return [$item->day, $item->total];
            }
        )->toArray();
        
        
        $browsers_clicks = $url->clicks()->select(\DB::raw('count(id) as total'),'browser')->groupBy('browser')->get()->map(
            function ($item)
            {
                return [$item->browser, $item->total];
            }
        )->toArray();
        
        
        $platform_clicks = $url->clicks()->select(\DB::raw('count(id) as total'),'platform')->groupBy('platform')->get()->map(
            function ($item)
            {
                return [$item->platform, $item->total];
            }
        )->toArray();
        
        return view('user.show', ['url' =>$url, 'browsers_clicks' => $browsers_clicks, 'daily_clicks' => $daily_clicks, 'platform_clicks' => $platform_clicks]);
    }
    
    public function generateShortUrl()
    {
       $valid_chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
       
       $database = Url::select('short_url')->pluck('short_url')->toArray();
       
       do {
            $short_url = substr(str_shuffle($valid_chars), 0, 5);
       } while (in_array($short_url, $database));
       
       return $short_url;
    }
}
