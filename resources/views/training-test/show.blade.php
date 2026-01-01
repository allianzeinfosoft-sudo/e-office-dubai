<h4>{{ $trainingTest->title }}</h4>
<p>Total Marks: {{ $trainingTest->total_marks }}</p>

<hr>

@foreach($trainingTest->questions as $index => $question)
    <div class="mb-4">
        <strong>Q{{ $index + 1 }}. {{ $question->question }}</strong>

        <ul class="mt-2">
            <li>A. {{ $question->option_a }}</li>
            <li>B. {{ $question->option_b }}</li>
            <li>C. {{ $question->option_c }}</li>
            <li>D. {{ $question->option_d }}</li>
        </ul>
        <small class="text-muted">Marks: {{ $question->marks }}</small>
    </div>
@endforeach
