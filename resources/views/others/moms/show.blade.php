<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th> Title </th>
                                    <th colspan="3"> {{ $mom->mom_title }} </th>
                                </tr>
                                <tr>
                                    <th width="25%">Date</th>
                                    <th width="25%"> {{ $mom->mom_date }} </th>
                                    <th width="25%">Created By</th>
                                    <th width="25%"> {{ $mom->employee->full_name }} </th>
                                </tr>
                                <tr>
                                    <th>Assigned To</th>
                                    <th colspan="3"> {{ implode(', ', $mom->AssignedToEmployee) }} </th>
                                </tr>
                                <tr>
                                    <td colspan="4"> {!! $mom->mom_details !!} </td>
                                </tr>
                                <tr>
                                    <td colspan="4"> {!! $mom->attachments ? ' <a href="' . asset('storage/moms/' . $mom->attachments) . '" target="_blank"><i class="ti ti-file"></i> </a>' : '' !!} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>