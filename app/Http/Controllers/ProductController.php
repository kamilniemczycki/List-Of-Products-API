<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getProducts()
    {
        $output = [];
        $output['success'] = false;
        try {
            $output['products'] = DB::table('products')
                ->select('name', 'description', 'image_url', 'price')
                ->whereNull('deleted_at')
                ->get();

            $output['success'] = true;
        } catch (\Exception $e) {
            $output['error'] = $e->getMessage();
        }
        return response()->json($output);
    }
}
