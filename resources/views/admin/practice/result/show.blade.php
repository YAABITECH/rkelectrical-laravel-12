@extends('layout.admin.structure')
@section('xmt_tit', 'Practice Report | Admin')
<style type="text/css">
.table {
    margin: 0 auto;
    width: 80%;
}
</style>
@section('content')
<div class="container text-center">
    <div class="border border-primary border-2 rounded m-5 align-self-center">
    <h2 class=" border border-primary border-2 bg-primary text-light p-2">Test Result</h2>
    <div class="col-12">
        <div id="result mt-2">
            <table class="table ">
                <tr >
                    <th class="fw-bold p-2 text-center">Participant Name :</th>
                    <td>{{ $practice->user->name }}</td>
                </tr>
                <tr>
                    <th class="fw-bold p-2 text-center">Subject :</th>
                    <td>{{ $practice->practiceSubject->name }} > {{ $practice->practiceTopic->name }} > {{ $practice->practiceSubtopic->name  }}</td>
                </tr>
                <tr>
                    <th class="fw-bold p-2 text-center">Attended :</th>
                    <td>{{ $practice->attended }}</td>
                </tr>
                <tr>
                    <th class="fw-bold p-2 text-center">Answered :</th>
                    <td>{{ $practice->answered }}</td>
                </tr>
                <tr>
                    <th class="fw-bold p-2 text-center">Right Ans :</th>
                    <td>{{ $practice->right_answer }}</td>
                </tr>
                <tr>
                    <th class="fw-bold p-2 text-center">Wrong Ans :</th>
                    <td>{{ $practice->wrong_answer }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
