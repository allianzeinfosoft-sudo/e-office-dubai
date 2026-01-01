<div class="d-flex gap-1">

    <!-- View Question Paper -->
    <a href="{{ route('training-tests.show', $test->id) }}"
       class="btn btn-sm btn-info"
       title="View Question Paper">
        <i class="fa fa-eye"></i>
    </a>

    @if(in_array($user->role, ['Developer','HR','G1']))
        <!-- Admin Edit -->
        <a href="{{ route('training-tests.edit', $test->id) }}"
           class="btn btn-sm btn-primary">
            <i class="fa fa-edit"></i>
        </a>
    @endif

</div>
