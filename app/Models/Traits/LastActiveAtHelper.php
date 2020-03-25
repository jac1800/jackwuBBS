<?php


namespace App\Models\Traits;

use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

trait LastActiveAtHelper
{
    protected $hash_prefix="larabbs_last_actived_at_";
    protected $field_prefix="user_";

    public function recordLastActiveAt()
    {
        // 获取今天的日期
        $date = Carbon::now()->toDateString();

        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateString($date);

        // 字段名称，如：user_1
        $field = $this->getHashField();

       // dd(Redis::hGetALL($hash));

        // 当前时间，如：2017-10-21 08:35:15
        $now = Carbon::now()->toDateTimeString();

        // 数据写入 Redis ，字段已存在会被更新
        Redis::hSet($hash, $field, $now);

    }

    public function syncUserActivedAt()
    {
        $yesteday_date=Carbon::yesterday()->toDateString();
        $hash = $this->getHashFromDateString($yesteday_date);

        $datas=Redis::hGetAll($hash);

        foreach ($datas as $user_id =>$actived_at) {
            // 会将 `user_1` 转换为 1
            $user_id = str_replace($this->field_prefix, '', $user_id);

            // 只有当用户存在时才更新到数据库中
            if ($user = $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
       }
        // 以数据库为中心的存储，既已同步，即可删除
        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value)
    {
        //获取今日时间
        $date=Carbon::now()->toDateString();
        $hash=$this->getHashFromDateString($date);
        $field=$this->getHashField();

        $datetime =Redis::hGet($hash,$field) ?: $value;

        //如果存在则返回时间对应的 Carbon 实体，否则从数据库获取
        if($datetime){
            return new Carbon($datetime);
        }else{
            return $this->created_at;
        }

    }

    public function getHashFromDateString($date)
    {
        return $this->hash_prefix . $date;
    }

    public function getHashField()
    {
        return $this->field_prefix . $this->id;
    }
}