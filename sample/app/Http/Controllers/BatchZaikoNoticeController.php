<?php

namespace App\Http\Controllers;

use App\Models\ItemList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BatchZaikoNoticeController extends Controller
{
    public function notice(Request $request)
    {
        $item = new ItemList();
        $item_lists = $item->itemAll();
        $sendflg = false;
        $itemnamelist = array();
        foreach($item_lists as $itemlist)
        {
            if($item->isBelowMinzaiko($itemlist->zaiko,$itemlist->minzaiko))
            {
                array_push($itemnamelist,$itemlist->name);
            }
            $sendflg = true;
        }
        if($sendflg)
        {
            $item->sendMail($itemnamelist);
        }
        return "success";
    }
}
