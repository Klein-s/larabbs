<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmailContract
{

    use MustVerifyEmailTrait;
    use Notifiable{
        notify as protected laravelNotify;
    }
    use HasRoles;
    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;

    public function notify($instance)
    {
        if ($this->id===Auth::id()){
            return;
        }

        if (method_exists($instance,'toDatabase')){
            $this->increment('notification_count');
        }
        $this->laravelNotify($instance);
    }

    public function markAsRead(){
        $this->notification_count=0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {

            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path){

        if (!\Str::startsWith($path,'http')){

            //拼接完整的url
            $path=config('app.url')."/uploads/images/avatars/$path";
        }
        $this->attributes['avatar']=$path;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone','email', 'password','introduction','avatar',
        'weixin_openid', 'weixin_unionid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function topics(){
        return $this->hasMany(Topic::class);
    }
    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function isAuthorOf($model){
        return $this->id===$model->user_id;
    }
}
