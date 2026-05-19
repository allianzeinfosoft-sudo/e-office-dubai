<div class="row">
     <div class="col-sm-12">
          <div class="table-responsive">
                <table class="table table-bordered" id="wfh-wsf-report-table">
                    <thead>
                         <tr>
                              <th>Employee Name</th>
                              <th>Signin Date</th>
                              <th>Signin Time</th>
                              <th>Signout Time</th>
                              <th>Working Hours</th>
                              <th>Type</th>
                              <th>Status</th>
                         </tr>
                    </thead>
                    <tbody>
                         @foreach($wfs_wfh_reports as $report)
                         <tr>
                              <td>{{ $report->employee->full_name }}</td>
                              <td>{{ date('d-m-Y', strtotime($report->signin_date)) }}</td>
                              <td>{{ $report->signin_time }}</td>
                              <td>{{ $report->signout_time }}</td>
                              <td>{{ $report->working_hours }}</td>
                              <td>
                                   <span class="badge bg-label-{{ $report->status == 'wfh' ? 'primary' : 'info' }}">
                                        {{ strtoupper($report->status) }}
                                   </span>
                              </td>
                              <td>
                                   @if($report->approvel_status == 0)
                                        <span class="badge bg-label-warning">Pending</span>
                                   @elseif($report->approvel_status == 1)
                                        <span class="badge bg-label-success">Approved</span>
                                   @else
                                        <span class="badge bg-label-danger">Rejected</span>
                                   @endif
                              </td>
                         </tr>
                         @endforeach
                    </tbody>
               </table>
          </div>
     </div>
</div>