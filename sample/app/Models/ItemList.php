<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * @author wakairyou
 *
 */
class ItemList extends Model
{
    use HasFactory;
    
    // モデルに関連付けるテーブル
    protected $table = 'item_lists';
    
    // テーブルに関連付ける主キー
    protected $primaryKey = 'id';
    
    // 登録・更新可能なカラムの指定
    protected $fillable = [
        'id',
        'name',
        'zaiko',
        'itemimage',
        'minzaiko',
        'updated_at',
        'created_at'
    ];
    public static $createError;
    
    // ディレクトリ名
    public static $dir = 'public/itemimage';
    
    /**
     * 一覧画面表示用にbooksテーブルから全てのデータを取得
     */
    public function itemAll()
    {
        return $this::all();
    }
    
    /**
     * 一覧画面表示用にbooksテーブルから全てのデータ件数を取得
     */
    public function itemAllCount()
    {
        return $this::all()->count();
    }
    
    /**
     * 入力項目のチェック
     */
    public function check($request)
    {
        $itemname = empty($request->input('editline')) ? 
                    $request->input('item-name')
                    :$request->input('item-name'.$request->input('editline'));
//        
        $zaiko = empty($request->input('editline')) ?
                    $request->input('zaiko')
                    :$request->input('zaiko'.$request->input('editline'));
        //　入力チェック
        if(empty($itemname)){
            $this::$createError = "消耗品名が空です。";
            return false;
        }

        if(empty($zaiko)){
            $this::$createError = "数量が空もしくは数値以外が入力されています。";
            return false;
        }
        return true;
    }
    /**
     * item_listsテーブルへの商品情報登録処理
     */
    public function itemInsert($request)
    {
        $imagename = $this->getItemName($request);
        $imagedir = !empty($this->getFileName($request,$imagename)) ?
                   $this::$dir.'/'.$this->getFileName($request,$imagename)
                   : "";
        return $this->create([
            'id' =>  $this->getNewItemId(),
            'name' => $request->input('item-name'),
            'zaiko' => $request->input('zaiko'),
            'minzaiko' => $request->input('minzaiko'),
            'itemimage'=> $imagedir
        ]);
    }
    /**
     * id生成処理
     */
    public function getNewItemId()
    {
        $lastitem = $this::orderBy('id','desc')->first();
        return $lastitem->id + 1;
    }
    
    /**
     * item_listのレコード削除処理
     */
    public function itemDelete($request)
    {
       $item_id = $request->input('id'.$request->input('delline'));
       return $this->destroy($item_id);
    }
    
    /**
     * item_listのレコード更新処理
     */
    public function itemUpdate($request)
    {
        //　商品更新処理
        $id = 'id'.$request->input('editline');
        $item_name ='item-name'.$request->input('editline');
        $zaiko = 'zaiko'.$request->input('editline');
        $minzaiko = 'minzaiko'.$request->input('editline');
        $item = $this::find($request->input($id));
        $imagename = $this->getItemName($request);
        $imagedir = !empty($this->getFileName($request,$imagename)) ?
                    $this::$dir.'/'.$this->getFileName($request,$imagename)
                    : "";
        Log::debug($this::$dir.'item-image'.$request->input('editline'));
        $item->update([
            "name" => $request->input($item_name),
            "zaiko" => $request->input($zaiko), 
            "minzaiko" => $request->input($minzaiko), 
            'itemimage'=> $imagedir
        ]);
        return null;
    }
    
    /**
     * 画像保存処理
     */
    public function savedImage($request)
    {
        $imagename = $this->getItemName($request);
        $imagepos = $this->getImagePos($request);
        $imagefilename = $this->getFileName($request,$imagename);
        if(!empty($request->file($imagepos))){
            $request->file($imagepos)->storeAs($this::$dir, $imagefilename);
        }
        return null;
    }
    
    /**
     * ファイル名取得処理
     */
    public function getItemName($request)
    {
        // 画像保存用のファイル名を生成する。
        //新規登録の場合、item-image[新規で採番したITEMID]
        //編集の場合、item-image[ITEMID]
        return empty($request->input('editline'))
        ? 'item-image'.$this->getNewItemId()
        : 'item-image'.$request->input('editline');
    }
    
    /**
     * 画像ファイルが格納されているname属性取得処理
     */
    public function getImagePos($request)
    {
        //新規登録の場合、item-image
        //編集の場合、item-image[ITEMID]
        return empty($request->input('editline'))
            ? 'item-image'
            : 'item-image'.$request->input('editline');
    }
    
    /**
     * 拡張子付ファイル名生成処理
     */
    public function getFileName($request,$imagename)
    { 
        $existimage = $this->getExistImage($request);
        if(!empty($existimage)){
            return $existimage;
        }
        //画像ファイル名生成処理
        $imagename = $this->getItemName($request);
        $imagepos = $this->getImagePos($request);
        //　アップロードしたファイルの拡張子を取得する。
        if(!empty($request->file($imagepos))){
            $extention = $request->file($imagepos)->getClientOriginalExtension();
            return $imagename.".".$extention;
        } else{
            return "";
        }
            
    }
    
    /**
     * 登録済画像ファイル名取得処理
     */
    public function getExistImage($request)
    {
        $itemimage = explode("/",DB::table($this->table)->where('id', $request->input('editline'))->value('itemimage'));
       Log::debug($itemimage[2]);
       return $itemimage[2];
    }
    
    /**
     * 商品絞り込み検索
     */
    public function getExistItem($request)
    {
        return DB::table($this->table)->where('name', 'LIKE','%'.$request->input('searchname').'%')->get();
    }
    
    /**
     * 商品絞り込み検索件数
     */
    public function getExistItemCount($request)
    {
        return DB::table($this->table)->where('name', 'LIKE','%'.$request->input('searchname').'%')->get()->count();
    }
    
    /**
     * 最低在庫数が下回っているかどうか
     * @return true :各商品の在庫数が最低在庫数を下回っている
     * @return false :各商品の在庫数が最低在庫数を下回っていない
     */
    public function isBelowMinzaiko($zaiko,$minzaiko)
    {
        // 各商品の在庫数が最低在庫数を下回っているかどうか判定する。
        //　最低在庫数が未設定の場合と在庫数が最低在庫数以上の場合はfalse
        if(!empty($minzaiko) && $zaiko < $minzaiko){
            return true;
        }
        return false;
    }
    
    /**
     * 最低在庫僅少メールを送信する。
     */
    public function sendMail($itemnamelist)
    {
        //在庫僅少メールを送信する。
        $itemnamecomma ="";
        $i = 0;
        foreach ($itemnamelist as $itemname)
        {
            $itemnamecomma = $i == 0 ? $itemname : $itemnamecomma."】,【".$itemname;
            $i++;
        }
        Mail::send([], [], function ($message) use ($itemnamecomma) {
            $message->to('111594cockroach@gmail.com');
            $message->subject('在庫僅少のお知らせ');
            $message->setBody('商品名 【'.$itemnamecomma.'】 の在庫が最低在庫数を下回りました。');
        });
    }
}
