<?php

namespace App\Http\Controllers;

use App\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $story = Story::all();
        return view('admin.story_mgmt',compact('story'));
        // return view('admin.create_story');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        return view('admin.create_story');
      
        
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|max:255',
            'writer'=> 'required',
            'department' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if($request->hasFile('image') && $request->image->isValid()){
            $extension = $request->image->extension();
            $filename = time()."_.".$extension;
            $request->image->move(public_path('images'),$filename);
        }else{
            $filename = "no-image.jpg";
        }
        $story = new Story;
        $story->title = $request->title;
        $story->image = $filename;
        $story->short_description = $request->input('short_description');
        $story->description = $request->input('description');
        $story->writer_id = $request->input('writer');
        $story->dep_id = $request->input('department');
        $story->status = $request->input('status');
        // dd($story);
        ;
        if($story->save()){
            return redirect('stories')->with('message','Story Successfully Created');
        }else{
            return redirect('stories/create')->with('message','Failed to create story');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function show()
    {   
        $story = Story::all();
        return view('admin.story_mgmt',compact('story'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit_story = Story::find($id);
        return view('admin.story_edit',compact('edit_story'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $update_story = Story::find($id);
        if($request->hasFile('image') && $request->image->isValid()){
            $extension = $request->image->extension();
            $filename = time()."_.".$extension;
            $request->image->move(public_path('images'),$filename);
        }else{
            $filename = "no-image.jpg";
        }
        $update_story->title = $request->input('title');
        $update_story->image = $filename;
        $update_story->short_description = $request->input('short_description');
        $update_story->description = $request->input('description');
        $update_story->status = $request->input('status');
        $update_story->writer_id = $request->input('writer');
        $update_story->dep_id = $request->input('department');
        // dd($update_story);
        if($update_story->save()){
            return redirect("stories")->with('message','Story Successfully Updated');
        }else{
            return redirect("stories/$update_story->id/edit")->with('message','Failed To Updated');
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy(Story $story)
    {
        $story->delete();
        if($story->delete()){
            return redirect("story/$update_story->id/edit")->with('message','Story Successfully Deleted');
        }else{
            return redirect("story/$update_story->id/edit")->with('message','Failed To Delete');
        }
    }
}
