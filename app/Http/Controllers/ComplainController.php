<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Complaint;

class ComplainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Complaint::with('user.detailUser')->where('status',1)->get();
        // $org = \App\Model\DetailUser::where('user_id',6);
        return view('dashboard.complain.index',compact(['data']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.order.complain');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new Complaint;
        $data -> subject = $request -> subject;
        $data -> complaint_desc = $request -> complaint_desc;
        $data -> user_id = auth()->user()->id;
        $data -> status = 1;
        // dd($data);
        $data -> save();

        return redirect()->route('user.complain')->with('alert-success','Your complaint has been sent!');
    }
    public function reply(Request $request,$id)
    {
        $data = Complaint::find($id);
        $data -> pic = $request -> pic;
        $data -> date_to_meet = $request -> date_to_meet;
        $data -> status = 2;
        // dd($data);
        $data -> save();

        return redirect()->route('user.complain')->with('alert-success','Your complaint has been sent!');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
