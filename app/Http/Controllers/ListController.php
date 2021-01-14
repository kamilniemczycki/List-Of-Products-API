<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{

    public function getLists()
    {
        $output = [];
        $output['success'] = false;
        try {
            $output['lists'] = DB::table('lists')
                ->join('users_to_lists', 'users_to_lists.list_id', '=', 'lists.id')
                ->join('users', 'users.id', '=', 'users_to_lists.user_id')
                ->select('lists.id', 'lists.name', 'lists.description')
                ->whereNull('lists.deleted_at')
                ->whereNull('users_to_lists.deleted_at')
                ->where('users.id', Auth::id())
                ->get();

            $output['success'] = true;
        } catch (\Exception $e) {
            $output['error'] = $e->getMessage();
        }
        return response()->json($output);
    }

    public function createList(Request $request)
    {
        $output = [];
        $output['success'] = false;
        try {

            $listId = DB::table('lists')->insertGetId([
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]);
            $output['listId'] = $listId;
            DB::table('users_to_lists')->insertGetId([
                'user_id' => Auth::id(),
                'list_id' => $listId
            ]);

            $output['success'] = true;
        } catch (\Exception $e) {
            $output['error'] = $e->getMessage();
        }
        return response()->json($output);
    }

    private function checkPermissions($idList)
    {
        if (!DB::table('users_to_lists')
            ->where('user_id', Auth::id())
            ->where('list_id', $idList)
            ->whereNull('deleted_at')
            ->exists()) {
            $output = [];
            $output['success'] = false;
            $output['error'] = 'You have not permissions or list don\'t exist';
            echo json_encode($output);
            die();
        }
    }

    public function listDetails($id)
    {
        $this->checkPermissions($id);
        $output = [];
        $output['success'] = false;
        try {
            $list = DB::table('lists')
                ->select('lists.id', 'lists.name', 'lists.description')
                ->where('lists.id', $id)
                ->first();
            $output['listDetails'] = $list;

            $output['products'] = DB::table('products')
                ->join('products_to_lists', 'products_to_lists.product_id', '=', 'products.id')
                ->join('lists', 'products_to_lists.list_id', '=', 'lists.id')
                ->select('id', 'name','description','image_url','price', 'is_done as done')
                ->whereNotNull('products_to_lists.deleted_at')
                ->whereNotNull('products.deleted_at')
                ->get();

            $output['success'] = true;
        } catch (\Exception $e) {
            $output['error'] = $e->getMessage();
        }
        return response()->json($output);
    }
}
