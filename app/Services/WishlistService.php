<?php
namespace App\Services;
use App\Repositories\WishlistRepository;


class WishlistService{
    protected $WishlistRepo;

    public function __construct(WishlistRepository $WishlistRepo){
        $this->WishlistRepo=$WishlistRepo;
    }

    public function createWishList($courseId){
        $user = auth()->user();
        if (!$user || $user->role !== 'student'){
            return response()->json(['error' => 'non autorise'], 403);
        }
        return $this->WishlistRepo->addToWishlist([
            'student_id' => $user->id,
            'course_id' => $courseId,
        ]);
    }

    public function deleteWishList($courseId){
        $user = auth()->user();
        if (!$user || $user->role !== 'student') {
            return response()->json(['error' => 'non autorise'], 403);
        }
        return $this->WishlistRepo->removeFromWishlist($user->id, $courseId);
    }
    public function getWishListUser(){
        $user=auth()->user();
        if (!$user || $user->role !== 'student') {
            return response()->json(['error' => 'non autorise'], 403);
        }
            return $this->WishlistRepo->getUserWishlist($user->id);
    }
}