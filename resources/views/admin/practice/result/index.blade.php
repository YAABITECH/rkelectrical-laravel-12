@extends('layout.admin.structure')
@section('xmt_tit', 'Practice Result | Admin')

@section('content')
    
<br>
<div class="container text-center">
    <table class="table table-dark table-bordered table-hover table-striped">
        <tr>
            <th>User Name</th>
            <th>Subject</th>
            <th>Topic</th>
            <th>Subtopic</th>
            <th>Attended</th>
            <th>Answered</th>
            <th>Right_answer</th>
            <th>Wrong_answer</th>
            <th>Duration</th>
            <th>Action</th>
            <th>Result</th>
        </tr>
        @foreach($practice as $practice)
        <tr>
        <td>{{ $practice->user->name ?? 'N/A' }}</td>
            <td>{{ $practice->practiceSubject->name ?? 'N/A' }}</td>
            <td>{{ $practice->practiceTopic->name ?? 'N/A' }}</td>
            <td>{{ $practice->practiceSubtopic->name ?? 'N/A' }}</td>
            <td>{{ $practice->attended }}</td>
            <td>{{ $practice->answered }}</td>
            <td>{{ $practice->right_answer }}</td>
            <td>{{ $practice->wrong_answer }}</td>
            <td>{{ $practice->duration }}</td>
            <td> 
                <form action="{{ route('admin.practice.result.destroy', $practice->id) }}" method="post"
                    style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-xmark"></i></button>
                </form>
            </td>
            <td> 
                <form action="{{ route('admin.practice.result.show',  $practice->id) }}" method="get" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning">Show</button>
                </form>

                </td>
            </tr>
        @endforeach
    </table>

</div>
   


@endsection
