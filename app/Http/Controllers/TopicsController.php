<?php

namespace App\Http\Controllers;

use App\Handlers\ImagesUploadHandles;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request,Topic $topic)
	{
		$topics =$topic->withOrder($request->order)->with("category","user")->paginate();
		return view('topics.index', compact('topics'));
	}

    public function show(Request $request,Topic $topic)
    {
        // URL 矫正
        if ( ! empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }
        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
	    $categories=Category::all();
		return view('topics.create_and_edit', compact('topic',"categories"));
	}

	public function store(TopicRequest $request,Topic $topic)
	{
	    $topic->fill($request->all());
	    $topic->user_id =Auth::id();
	    $topic->save();
		//$topic = Topic::create($request->all());
		//return redirect()->route('topics.show', $topic->id)->with('success', '创建帖子成功');
		return redirect()->to($topic->link())->with('success', '创建帖子成功');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
	    $categories=Category::all();
		return view('topics.create_and_edit', compact('topic',"categories"));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		//return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
		return redirect()->to($topic->link())->with('message', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', '成功删除帖子.');
	}

    public function uploadImage(Request $request,ImagesUploadHandles $uploader)
    {
         $data=[
             "success"=>false,
             "msg"=>"上传失败",
             "file_path"=>""
         ];
         if($request->upload_file){
             $file=$request->upload_file;
             $result=$uploader->save($file,"topics",Auth::id(),1024);
             if($result){
                 $data['success']=true;
                 $data['msg']="上传成功";
                 $data['file_path']=$result['path'];
             }
         }
         return $data;
	}
}