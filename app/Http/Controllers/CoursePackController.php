<?php

namespace App\Http\Controllers;

use App\Models\CoursePack;
use App\Models\Course;
use Illuminate\Http\Request;

class CoursePackController extends Controller
{
    public function coursePack($packId){
        $coursePack = CoursePack::where('id',$packId)->first();
        $courses = Course::where('course_pack',$coursePack->id)->get();
        return view('course.packDetail',compact('coursePack','courses'));
    }
    public function index()
    {

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CoursePack  $coursePack
     * @return \Illuminate\Http\Response
     */
    public function show(CoursePack $coursePack)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CoursePack  $coursePack
     * @return \Illuminate\Http\Response
     */
    public function edit(CoursePack $coursePack)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CoursePack  $coursePack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CoursePack $coursePack)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CoursePack  $coursePack
     * @return \Illuminate\Http\Response
     */
    public function destroy(CoursePack $coursePack)
    {
        //
    }
}
