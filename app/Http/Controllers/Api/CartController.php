<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function getUserCart()
    {
        try{
            $user=Auth::user();
            $cart=$user->cart;
            foreach($cart as $item){
                $article = Article::find($item->article_id);
                $data []=[
                        'title'=>$article->title,
                        'price'=>$article->price,
                        'summary'=>$article->summary,
                        'cover_file_path'=>asset($article->cover_file_path),
                ];
            }
            return response()->json(['success' => true,'data'=>$data], 200);
        }catch(Exception $e){
            return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
        }
    }

    public function addToCart(Request $request)
    {
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $exist = $user->cart->where('article_id',$request->article_id)->first();
            if($exist){
                return response()->json(['success' => false,'message'=>'You cannot add article twice to cart'], 400);
            }
            $user->cart()->create(['article_id'=>$request->article_id]);
            DB::commit();
            return response()->json(['success' => true,'message'=>'item added to cart successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
        }
    }
    public function DeleteFromCart(Request $request)
    {
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $item=$user->cart->where('article_id',$request->article_id)->first();
            $item->delete();
            DB::commit();
            return response()->json(['success' => true,'message'=>'item deleted successfully'], 200);
        }catch (Exception $e) {
            return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
        }
    }
    public function DeleteAllCart(Request $request)
    {
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $user->cart()->delete();
            DB::commit();
            return response()->json(['success' => true,'message'=>'item deleted successfully'], 200);
        }catch (Exception $e) {
            return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
        }
    }
}
