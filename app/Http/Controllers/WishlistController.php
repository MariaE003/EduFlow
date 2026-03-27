<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WishlistService;

class WishlistController extends Controller
{
    protected $wishlistService;
    public function __construct(WishlistService $wishlistService){
        $this->wishlistService = $wishlistService;
    }

    public function store(Request $request){
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);
        $wish = $this->wishlistService->createWishList($request->course_id);
        return response()->json($wish);
    }

    public function destroy($courseId){
        $wish = $this->wishlistService->deleteWishList($courseId);

        return response()->json([
            'message' => 'Removed successfully',
            'data' => $wish
        ]);
    }
    public function index(){
        $wishlist = $this->wishlistService->getWishListUser();
        return response()->json($wishlist);
    }
}
