    @extends('layout.structure')
    @section('xmt_tit', 'RKElectrical Grid - Learning center')
    @section('xmt_des', 'Team of RKELECTRICAL GRID  for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures')
    @section('xmt_rob', 'index, follow')
    @section('xmt_can', '/')

    @section('content')
        <section class="pt-5 pb-3">
            <div class="container">
                <div class="bg-secondary mx-3 py-3 text-center text-white rounded-3">
                    <p class="mb-0">The course '{{ $course && $course->title ? $course->title : $coursepack->name }}' is selected. Add more or continue to pay.</p>
                </div>
            </div>
        </section>
        <section class="py-5">
            <div class="container">
                <h5 class="text-center text-info">Select Your Packages</h5>
                <div class="btn-group">
                    <a href="#" class="btn border-primary btn-primary active" aria-current="page">Paid Course</a>
                    <a href="#" class="btn border-primary" aria-current="page">Online Test</a>
                </div>
                <div class="mt-3 shadow rounded-3 p-3">
                    @foreach($coursepacks as $coursePack)
                        <div class="mt-3">
                            @php
                                $isPurchasedPack = in_array($coursePack->id, $purchasedCoursePackIds);
                            @endphp
                            <input class="form-check-input coursePack-checkbox"
                                type="checkbox"
                                value="{{ $coursePack->fees }}"
                                name="selectedCoursepacks[]"
                                id="coursePack{{ $coursePack->id }}"
                                @if($isPurchasedPack)
                                    disabled
                                @endif
                                @if($coursepack && $coursepack->id == $coursePack->id && $course == null && !$isPurchasedPack)
                                    checked
                                @endif>
                            <label class="form-check-label" for="coursePack{{ $coursePack->id }}">
                                {{ $coursePack->name }}
                                <span class="text-info fw-bold">( ₹{{ $coursePack->fees }} )</span>
                                <span class="text-danger fw-bold">{{$isPurchasedPack ? 'Already Purchased' : ''}}</span>
                            </label>
                            <ul class="list-group mt-3">
                                @foreach($coursePack->getCourses as $allCourse)
                                    @php
                                        $isPurchasedCourse = in_array($allCourse->id, $purchasedCourseIds);
                                    @endphp
                                    <li class="list-group-item">
                                        <input class="form-check-input course-checkbox"
                                            type="checkbox"
                                            value="{{ $allCourse->fees }}"
                                            name="selectedCourses[]"
                                            id="course{{ $allCourse->id }}"
                                            data-course-pack-id="{{ $coursePack->id }}"
                                            data-purchased-course-pack-id="{{ $isPurchasedCourse || $isPurchasedPack}}"
                                            @if($course && $allCourse->id == $course->id && !$isPurchasedCourse)
                                                checked
                                            @endif
                                            @if($isPurchasedCourse || $isPurchasedPack)
                                                disabled
                                            @endif>

                                        <label class="form-check-label" for="course{{ $allCourse->id }}">
                                            {{ $allCourse->title }}
                                            <span class="text-info fw-bold">( ₹{{ $allCourse->fees }} )</span>
                                            <span class="text-danger fw-bold">{{$isPurchasedCourse ? 'Already Purchased' : ''}}</span>

                                        </label>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
        <section class="py-3 pb-5">
            <div class="container">
                <div class="card py-5 text-center shadow border-0">
                    <div>
                        <p class="text-info fw-bold">Total Amount: ₹<span id="totalAmount">0</span></p>
                        <form id="paymentForm" action="{{ route('payment.gateway') }}" method="POST">
                            @csrf
                            <input type="text" name="selectedCourses" id="selectedCourses">
                            <input type="text" name="totalFees" id="totalFees" value="">
                            <input type="text" name="coursePack" id="coursePack" value="{{ $coursepack->name }}">
                            <input type="text" name="user" value="{{ $user}}">
                            <input type="text" name="previousUrl" value="{{ $previousUrl}}">
                            <button type="button" class="btn btn-primary" onclick="submitForm('gateway')">Pay via Payment Gateway</button>
                            <p class="mt-3">(Pay using ATM card / Debit Card / Credit Card)</p>
                        </form>
                    </div>
                    <h5>OR</h5>
                    <div>
                        <form id="manualForm" action="{{ route('payment.manual') }}" method="POST">
                            @csrf
                            <input type="text" name="selectedCourses" id="selectedCoursesManual">
                            <input type="text" name="totalFees" id="totalFeesmanual" value="">
                            <input type="text" name="coursePack" id="coursePackmanual" value="">
                            <input type="text" name="user_id" value="{{ $user->id }}">
                            <input type="text" name="previousUrl" value="{{ $previousUrl}}">
                            <button type="text" class="btn btn-danger" onclick="submitForm('manual')">Pay Manually</button>
                            <p class="mt-3">(Pay using Gpay, PhonePe, PayTM, BHIM UPI, NEFT/Bank Transfer)</p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endsection

    @push('endjs')
    <script>
function updateTotal() {
    let totalAmount = 0;
    let selectedCoursePackIds = [];
    let selectedCourseIds = [];

    // Iterate over each course pack checkbox
    $('.coursePack-checkbox').each(function() {
        let coursePackId = $(this).attr('id').replace('coursePack', '');
        let relatedCourses = $('.course-checkbox').filter(function() {
            return $(this).data('course-pack-id') == coursePackId;
        });

        if ($(this).is(':checked')) {
            let coursePackFees = Math.round(parseFloat($(this).val()));
            totalAmount += coursePackFees;
            selectedCoursePackIds.push(coursePackId);

            // Disable and uncheck related courses
            relatedCourses.each(function() {
                if (!$(this).data('purchased-course-pack-id')) {
                    $(this).prop('checked', false).prop('disabled', true);
                }
            });

        } else {
            // Re-enable related courses if the course pack is unchecked and not purchased
            relatedCourses.each(function() {
                if (!$(this).data('purchased-course-pack-id')) {
                    $(this).prop('disabled', false);
                }
            });
        }
    });

    // Sum the amounts of the individually selected courses that are not part of a selected course pack
    $('.course-checkbox:checked').each(function() {
        if (!$(this).is(':disabled')) {
            let courseFees = Math.round(parseFloat($(this).val()));
            totalAmount += courseFees;
            selectedCourseIds.push($(this).attr('id').replace('course', ''));
        }
    });

    // Update the total amount in the UI and the hidden totalFees input fields
    $('#totalAmount').text(totalAmount);
    $('#totalFees').val(totalAmount);
    $('#totalFeesmanual').val(totalAmount);

    // Store the selected course pack IDs and course IDs in hidden inputs
    $('#coursePackmanual').val(selectedCoursePackIds.join(','));
    $('#coursePack').val(selectedCoursePackIds.join(','));
    $('#selectedCoursesManual').val(selectedCourseIds.join(','));
    $('#selectedCourses').val(selectedCourseIds.join(','));
}

function submitForm(paymentMethod) {
    updateTotal(); // Ensure that the latest selections are updated before submission

    if (paymentMethod === 'gateway') {
        $('#paymentForm').submit();
    } else if (paymentMethod === 'manual') {
        $('#manualForm').submit();
    }
}

$(document).ready(function() {
    // Attach event listeners
    $('.coursePack-checkbox').on('change', updateTotal);
    $('.course-checkbox').on('change', updateTotal);

    // Initial update
    updateTotal();
});







    </script>
    @endpush
