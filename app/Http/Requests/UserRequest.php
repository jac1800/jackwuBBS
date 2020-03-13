<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            "name"=>"required|between:3,12|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,".Auth::id(),
            "email"=>"required|email",
            "instruction"=>"max:12",
            "avatar"=>"mimes:jpg,jpeg,png,gif|dimensions:min_height:768,min_weight:768"
        ];
    }

    public function messages()
    {
        return [

            'name.required'=>"用户名不能为空",
            'name.between'=>"用户名 必须介于 3 - 12 个字符之间",
            'name.regex' => '用户名只支持英文、数字、横杠和下划线。',
            'name.unique' => '用户名已被占用，请重新填写',
            "avatar.mimes"=>'头像必须是 jpeg, bmp, png, gif 格式的图片',
            'avatar.dimensions' => '图片的清晰度不够，宽和高需要 768 以上',
        ];

    }
}
