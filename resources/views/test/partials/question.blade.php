<div class="question-block">
    <div class="card border-0 shadow-sm mb-3 rounded-3">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="text-info p-2"><span class="fw-semibold">Question Type :</span> Choice</div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="float-right p-2">Mark : {{ $question->mark }} &nbsp;|&nbsp; Negative : <span class="text-danger">
                        @if($question->negative_mark > 0)
                            {{ $question->negative_mark }}
                        @else
                            -
                        @endif
                    </span></div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="p-2">
                <strong class="text-primary fs-6">
                    <i class="fa-solid fa-hashtag me-1"></i>
                    Question No: {{ $question->priority }}
                </strong>
                @if($question->difficulty)
                    <span class="badge bg-info text-light">
                            {{ ucfirst($question->difficulty) }}
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="question-text mb-3 p-2">
        {!! $question->question !!}
    </div>
    @php
        $opts = [];
        if (!empty($question->options)) {
            if (is_string($question->options)) {
                $opts = json_decode($question->options, true) ?: [];
            } elseif (is_array($question->options)) {
                $opts = $question->options;
            }
        }
        $qtype = $question->question_type;
        $saved = isset($answerRow) ? $answerRow->answer : null;
        $savedArray = ($qtype === 'Multi-choice' && $saved) ? explode(',', $saved) : [];
    @endphp
    <input type="hidden"
           class="selected-answer"
           id="answer_field_{{ $question->id }}"
           value="{{ $saved }}">

    <div class="options-list">
        @if($qtype === 'Numerical')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Enter Answer</label>
                    <input type="number"
                           class="form-control numeric-input"
                           data-question="{{ $question->id }}"
                           placeholder="Type your answer"
                           value="{{ $saved }}">
                </div>
            </div>

        @elseif($qtype === 'Choice' || $qtype === 'Multi-choice')
            @foreach($opts as $idx => $opt)
                @php
                    $label = $opt['key'] ?? $idx;
                    $html  = $opt['value'];
                    $isSelected =
                        ($qtype === 'Choice'       && $saved == $idx)
                        || ($qtype === 'Multi-choice' && in_array($idx, $savedArray));
                @endphp

                <div class="option-card border rounded p-3 mb-2 selectable-option {{ $isSelected ? 'option-selected' : '' }}"
                     data-question="{{ $question->id }}"
                     data-value="{{ $idx }}"
                     data-type="{{ $qtype }}"
                     style="cursor:pointer; transition:0.2s">

                    <strong class="me-2">{{ $label }}.</strong>
                    <span>{!! $html !!}</span>
                </div>
            @endforeach
        @endif
    </div>
</div>
