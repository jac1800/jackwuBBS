<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\ActiveUserHelper;
use App\Models\Traits\LastActiveAtHelper;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;



class User extends Authenticatable implements MustVerifyEmailContract,JWTSubject
{
    use MustVerifyEmailTrait;
    use HasRoles;
    use ActiveUserHelper;
    use LastActiveAtHelper;

    use Notifiable {
        notify as protected myNotify;
    }

    public function notify($instance)
    {
        if($this->id === Auth::id()){
            return ;
        }
        // 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
        if(method_exists($instance,"toDatabase")){
            $this->increment("notification_count");
        }
        $this->myNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',"avatar","introduction",'phone','weixin_openid', 'weixin_unionid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'weixin_openid', 'weixin_unionid'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 确认作者身份
     * $var bool
     */
    public function isAuthorof($model)
    {
        return $this->id == $model->user_id;
    }

    public function topics()
    {
         return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function markAsRead()
    {
        $this->notification_count=0;
            $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function setAvatarAttribute($value)
    {
        if(! \Str::startsWith($value,"http")){
            $folder_name="uploads/images/avatar/";
            $this->attributes["avatar"]=config('app.url').'/'.$folder_name."/".$value;
        }
         $this->attributes["avatar"]=$value;
    }

    public function setPasswordAttribute($val)
    {
        //考虑到其他地方有可能已经将密码加密，需要长度判断
        if(strlen($val) != 60 ){
            $this->attributes['password']=bcrypt($val);
        }
         $this->attributes['password']=$val;
    }
    //重新定义getJWTIdentifier() 和 getJWTCustomClaims()
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
