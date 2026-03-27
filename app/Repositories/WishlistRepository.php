<?php 
namespace App\Repositories;

use App\Models\Wishlist;

class WishlistRepository{
    /* 
    ajouter un cours en favoris 
    ● supprimer un favori 
    ● afficher la wishlist 
    Guide : 
    ● vérifier que l’utilisateur est un étudiant 
    ● éviter les doublons dans la wishlist 
    
    */

    public function addToWishlist($data){
        return Wishlist::firstOrCreate([
            'student_id'=>$data['student_id'],
            'course_id'=>$data['course_id']
        ]);
    }
    public function removeFromWishlist($idStudent,$idCrourse){
        return Wishlist::where('student_id',$idStudent)->where('course_id',$idCrourse)->delete();
    }
    public function getUserWishlist($idStudent){
        return WishList::where('student_id',$idStudent)->get();
    } 


}