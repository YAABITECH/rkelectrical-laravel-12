<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\CoursePack;
use App\Models\BeforePay;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function manualPay(Request $request){
        $selectedCourseIDs = $request->input('selectedCourses');
        $courseIds = explode(',', $selectedCourseIDs);
        $courses = Course::whereIn('id', $courseIds)->get();
        $totalAmount = $request->input('totalFees');
        $selectedCoursePackIDs = $request->input('coursePack');
        $CoursePackIDs = explode(',', $selectedCoursePackIDs);
        $coursePacks = CoursePack::whereIn('id', $CoursePackIDs)->get();

        return view('payment.manual',compact('courses','totalAmount','coursePacks'));
    }
    public function paymentGateway(Request $request){
        $user = Auth::user();
        if($user == null){
            return redirect()->route('user.login');
        }
        $selectedCourseIDs = $request->input('selectedCourses');
        $courseIds = explode(',', $selectedCourseIDs);
        $selectedCoursePackIDs = $request->input('coursePack');
        $CoursePackIDs = explode(',', $selectedCoursePackIDs);
        $amount = $request->input('totalFees');
        $existing = BeforePay::where('user_id',$user->id)
                    ->where('course_id',$selectedCourseIDs)
                    ->where('coursepack_id',$selectedCoursePackIDs)
                    ->where('test_id',null)->first();
        if($existing){
            return redirect()->to($request->input('previousUrl'))->with('message', 'Course already purchased');;
        }
        $beforePay = BeforePay::create([
            'user_id' => $user->id,
            'course_id' => $selectedCourseIDs,
            'coursepack_id' => $selectedCoursePackIDs,
            'test_id' => null,
            'amount' => $amount,
        ]);
        $payload = [
            'purpose' => 'Transaction ID: ' . $beforePay->id,
            'amount' => $amount,
            'phone' => $user->phone,
            'buyer_name' => $user->name,
            'redirect_url' => route('payment.status', ['paymentId' => $beforePay->id]),
            // 'webhook' => route('payment.webhook'),
            'send_email' => true,
            'send_sms' => true,
            'email' => $user->email,
            'allow_repeated_payments' => false
        ];
        try {
            // $response = Http::withHeaders([
            //     'X-Api-Key' => 'a55956c4b015e732793d3231f1a9a928',
            //     'X-Auth-Token' => 'aea9dee3feba56ab6bac82a182098b33'
            // ])->post('https://www.instamojo.com/api/1.1/payment-requests/', $payload);

            $response = Http::withHeaders([
                'X-Api-Key' => 'test_f702d765cf4739afd40fdb82601', // Replace with your actual sandbox API key
                'X-Auth-Token' => 'test_911a1102bfebf4e49c4b2049e3d' // Replace with your actual sandbox Auth token
            ])->post('https://test.instamojo.com/api/1.1/payment-requests/', $payload);

            // Decode the response body
            $responseBody = $response->json();
            // Log response for debugging
            Log::info('Instamojo API Response', ['response' => $responseBody]);
            $paymentDetail = PaymentDetail::create([
                'user_id' => $user->id,
                'payment_id' => $responseBody['payment_request']['id'],
                'beforepay_id'=> $beforePay->id,
                'amount' => $amount,
                'status' => 'Pending'
            ]);
            if ($response->successful() && isset($responseBody['payment_request']['longurl'])) {
                $paymentDetail->update(['status' => 'Success']);
                $longurl = $responseBody['payment_request']['longurl'];
                Log::info('Redirecting to longurl', ['longurl' => $longurl]);
                return redirect($longurl);
            } else {
                // Log the error response
                Log::error('Instamojo Payment Initiation Failed', ['response' => $responseBody]);
                $paymentDetail->update(['status' => 'Failed']);
                return response()->json(['error' => 'Payment initiation failed.'], 500);
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Instamojo API Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Payment initiation failed due to an internal error.'], 500);
        }
    }

    public function paymentStatus(Request $request, $paymentId) {

        $paymentStatus = PaymentDetail::where('beforepay_id', $paymentId)->where('status','Success')->first();
        Log::info('Payment Status Request Received', ['paymentId' => $paymentStatus]);

        if (!$paymentStatus) {
            return response()->json(['error' => 'Payment details not found.'], 404);
        }
        $beforePay = BeforePay::find($paymentStatus->beforepay_id);
        return view('payment.status', compact('paymentStatus', 'beforePay'));
    }

    public function paymentWebhook(Request $request){
        return view('payment.webhook');
    }

}
