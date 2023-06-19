<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    /**
     * Handle categories stats.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function categories(Request $request)
    {
        if (!$request->expectsJson()) abort(403);

        $stats = DB::table('orders_count_by_category')
            ->get();

        return response()->json($stats, 200);
    }

    /**
     * Handle revenue stats.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function revenue(Request $request, string $type)
    {
        if (!$request->expectsJson()) abort(403);

        $stats = null;
        switch ($type) {
            case 'daily':
                $stats = DB::table('daily_income')->get();
                break;
            case 'monthly':
                $stats = DB::table('monthly_income')->get();
                break;
            case 'annual':
            default:
                $stats = DB::table('annual_income')->get();
                break;
        }

        return response()->json($stats, 200);
    }

    /**
     * Handle available cities.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cities(Request $request)
    {
        try {
            $request->validate([
                's' => ['required', 'string', 'min:3', 'max:30'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response('The given data was invalid.', 400);
        }

        $search = $request->s;

        $matches = Cache::remember('cities|' . $search, 60 * 60, function () use ($search) {
            $matches = [];
            if (($handle = fopen(resource_path('data/poland.csv'), "r")) !== false) {
                while ($row = fgetcsv($handle)) {
                    if (preg_match("/^$search/i", $city = $row[0])) {
                        $matches[] = $city;
                    }
                }

                fclose($handle);
            }

            return $matches;
        });

        return response()->json($matches, 200);
    }
}
