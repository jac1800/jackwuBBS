<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopicRequest extends FormRequest
{

    public function rules()
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            {
                return [
                    // CREATE ROLES
                    "title"=>"required|min:3",
                    "body"=>"required|between:3,256",
                    "category_id"=>"required|numeric"
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    // UPDATE ROLES
                    "title"=>"required|min:3",
                    "body"=>"required|between:3,256",
                    "category_id"=>"required|numeric"
                ];
            }
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            }
        }
    }

    public function messages()
    {
        return [
            // Validation messages
            "title.required"=>"请输入标题",
            "category_id.required"=>"请选择帖子类别",
            "title.min"=>"标题最少输入3个字符",
            "body.between"=>"内容只可以接受3~256个字符",
        ];
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
