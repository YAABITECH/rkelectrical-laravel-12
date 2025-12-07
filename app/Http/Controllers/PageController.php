<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Course;
use App\Models\PracticeSubject;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function home()
    {
        $testimonials = Testimonial::limit(3)->get();
        $subjects = PracticeSubject::where('status','public')->orderBy('priority','asc')->limit(3)->get();
        $courses=Course::limit(3)->get();
        return view('page.home',compact('testimonials','courses','subjects'));
    }
    public function about()
    {
        return view('page.about');
    }
    public function contact()
    {
        return view('page.contact');
    }
    public function terms()
    {
        return view('page.terms');
    }
    public function privacy()
    {
        return view('page.privacy');
    }
    public function material()
    {
        return view('material.index');
    }
    public function career()
    {
        return view('career.index');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'comment' => 'required|string',
        ]);

        Log::info('Contact Form Submission:', [
            'username' => $validatedData['username'],
            'phone' => $validatedData['phone'],
            'comment' => $validatedData['comment']
        ]);

        return redirect()->back()->with('success', 'Your message has been sent.');
    }
    public function calcy()
    {
        return view('test.calcy');
    }
}
