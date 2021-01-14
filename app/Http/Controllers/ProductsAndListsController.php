<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductsAndListsController extends Controller
{
    private function checkPermissions($idList, $idProduct)
    {
        if (!DB::table('users_to_lists')
            ->join('lists', 'lists.id', '=', 'users_to_lists.list_id')
            ->join('products_to_lists', 'lists.id', '=', 'products_to_lists.list_id')
            ->where('users_to_lists.user_id', Auth::id())
            ->where('users_to_lists.list_id', $idList)
            ->where('products_to_lists.product_id', $idProduct)
            ->whereNull('users_to_lists.deleted_at')
            ->whereNull('products_to_lists.deleted_at')
            ->exists()) {
            $output = [];
            $output['success'] = false;
            $output['error'] = 'You have not permissions or it don\'t exist';
            echo json_encode($output);
            die();
        }
    }

    public function getProductDetails($listId, $productId)
    {
        $this->checkPermissions($listId, $productId);
        $output = [];
        $output['success'] = false;
        try {
            $output['listDetails'] = DB::table('lists')
                ->select('lists.id', 'lists.name', 'lists.description')
                ->where('lists.id', $listId)
                ->first();

            $output['product'] = DB::table('users_to_lists')
                ->join('lists', 'lists.id', '=', 'users_to_lists.list_id')
                ->join('products_to_lists', 'lists.id', '=', 'products_to_lists.list_id')
                ->where('users_to_lists.user_id', Auth::id())
                ->where('users_to_lists.list_id', $listId)
                ->where('products_to_lists.product_id', $productId)
                ->whereNull('users_to_lists.deleted_at')
                ->whereNull('products_to_lists.deleted_at')
                ->select('id', 'name', 'description', 'image_url', 'price', 'is_done as done')
                ->first();

            $output['success'] = true;
        } catch (\Exception $e) {
            $output['error'] = $e->getMessage();
        }
        return response()->json($output);
    }
}
