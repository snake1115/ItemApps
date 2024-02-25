<?php

namespace App\Http\Controllers;

use App\Models\ItemList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItemListController extends Controller
{
    public function index(Request $request)
    {
        $item = new ItemList();
        return view('item_list.index', [
            'item_lists' => $item->itemAll(),
            'item_count' => $item->itemAllCount()
        ]);
    }
    public function search(Request $request)
    {
        $item = new ItemList();
        return view('item_list.index', [
            'item_lists' => $item->getExistItem($request),
            'item_count' => $item->getExistItemCount($request)
        ]);
    }
    public function insert(Request $request)
    {
        $item = new ItemList();
        
        if(!($item->check($request))){
            return view('item_list.index', [
                'item_lists' => $item->itemAll(),
                'item_count' => $item->itemAllCount(),
                'error' => ItemList::$createError
            ]);
        }
        $item->savedImage($request);
        $item->itemInsert($request);
        return view('item_list.index', [
            'item_lists' => $item->itemAll(),
            'item_count' => $item->itemAllCount(),
            'message'=>"登録が完了しました。"
        ]);
    }
    
    public function delete(Request $request)
    {
        $item = new ItemList();
        $item->itemDelete($request);
        return view('item_list.index', [
            'item_lists' => $item->itemAll(),
            'item_count' => $item->itemAllCount(),
            'message'=>"削除が完了しました。"
        ]);
    }
    
    public function update(Request $request)
    {
        $item = new ItemList();
        if(!($item->check($request))){
            return view('item_list.index', [
                'item_lists' => $item->itemAll(),
                'item_count' => $item->itemAllCount(),
                'error' => ItemList::$createError
            ]);
        }
        $item->savedImage($request);
        $item->itemUpdate($request);
        return view('item_list.index', [
            'item_lists' => $item->itemAll(),
            'item_count' => $item->itemAllCount(),
            'message'=>"更新が完了しました。"
        ]);
    }
    
}
