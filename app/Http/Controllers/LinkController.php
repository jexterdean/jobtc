<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Task;
use App\Models\LinkCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

use Redirect;

class LinkController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $links = Link::select('links.id','title','url','descriptions','tags',
            'comments',
            'link_categories.name as category_name')
            ->leftJoin('link_categories', 'link_categories.id','=','links.category_id')
            ->get();

        $categories = LinkCategory::all()
            ->lists('name','id')
            ->toArray();


        return view('links.index',[
            'assets'=> ['table'],
            'links'=> $links,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $from_dashboard = isset($request->is_dashboard) ? $request->is_dashboard : 0;
        unset($request->is_dashboard);

        $link = new Link($request->all());
        $link->save();

        $task = Task::find($request->task_id);

        $links = Link::select(
                'links.id', 'title',
                'url', 'descriptions',
                'tags', 'comments','task_id',
                'task_item_id', 'user_id',
                'link_categories.name as category_name'
            )
            ->leftJoin('link_categories', 'link_categories.id', '=', 'links.category_id')
            ->where('links.id', '=', $link->id)
            ->get();
        //return $request->task_id ? redirect()->route('project.show', $task->project_id) : redirect()->route('links.index');
        return $from_dashboard ? Redirect::back() : json_encode($links);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = LinkCategory::all()
            ->lists('name','id')
            ->toArray();

        $link = Link::find($id);

        return view('links.edit',[
            'assets'=> [],
            'link'=> $link,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        /** @var  $link Link */
        $link  = Link::find($id);

        $link->update($request->all());

        $links = Link::select(
            'links.id', 'title',
            'url', 'descriptions',
            'tags', 'comments','task_id',
            'task_item_id', 'user_id',
            'link_categories.name as category_name'
        )
            ->leftJoin('link_categories', 'link_categories.id', '=', 'links.category_id')
            ->where('links.id', '=', $id)
            ->get();

        return json_encode($links);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        /** @var  $link Link*/
        $link  = Link::find($id);
        $link->delete();

        return redirect()->route('links.index');
    }

    public function deleteLink($id)
    {

        /** @var  $link Link*/
        $link  = Link::find($id);
        if(count($link) > 0){
            $link->delete();
        }

        return redirect()->route('links.index');
    }

}
