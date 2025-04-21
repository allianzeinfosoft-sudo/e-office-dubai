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
                                    <th width="25%"> Title </th>
                                    <th width="75%"> {{ $companyPolicy->policyTitle }} </th>
                                </tr>
                                <tr>
                                    <th width="25%">Date</th>
                                    <th width="25%"> {{ $companyPolicy->policyStartDate }} </th>
                                </tr>
                                <tr>
                                    <td colspan="2"> {!! $companyPolicy->policyDescription !!} </td>
                                </tr>
                                <tr>
                                    <td colspan="4"> {!! $companyPolicy->attachments ? ' <a href="' . asset('storage/company_policies/' . $companyPolicy->attachments) . '" target="_blank"><i class="ti ti-file"></i> </a>' : '' !!} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>