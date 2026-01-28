<div class="row">
     <div class="col-sm-12">
          <div class="table-responsive">
               <table class="table table-bordered">
                    <thead>
                         <tr>
                              <th>Employee Name</th>
                              <th>Signin Date</th>
                              <th>Signin Time</th>
                              <th>Signout Time</th>
                              <th>Working Hours</th>
                         </tr>
                    </thead>
                    <tbody>
                         @foreach($wfs_wfh_reports as $report)
                         <tr>
                              <td>{{ $report->employee->full_name }}</td>
                              <td>{{ $report->signin_date }}</td>
                              <td>{{ $report->signin_time }}</td>
                              <td>{{ $report->signout_time }}</td>
                              <td>{{ $report->working_hours }}</td>
                         </tr>
                         @endforeach
                    </tbody>
               </table>
          </div>
     </div>
</div>