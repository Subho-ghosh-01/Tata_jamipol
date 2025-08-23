<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
// use Tymon\JWTAuth\Contracts\JWTSubject;

// class UserLogin extends Authenticatable implements JWTSubject
class UserLogin extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'userlogins'; 
    protected $guarded=[];
    // protected $fillable = ['name','password' ,'vendor_code' ,'user_type' ,'user_sub_type'];

    
    // public function getJWTIdentifier(){
    //     return $this->getKey();
    // }

    // public function getJWTCustomClaims(){
    //     return [];
    // }
    
}
