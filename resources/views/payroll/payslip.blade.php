<!DOCTYPE html>
<html>

<head>
     <meta charset="utf-8">
     <title>Payslip - {{ $entry->employee->full_name }}</title>
     <style>
          @page {
               margin: 10px;
          }
          body {
               font-family: 'Helvetica', sans-serif;
               font-size: 10px;
               color: #000;
               margin: 20px;
               padding: 5px;
          }
          .main-container {
               border: 0px solid #000;
               padding: 0;
          }
          table {
               width: 100%;
               border-collapse: collapse;
          }
          td, th {
               border: 1px solid #000;
               padding: 5px 5px;
               vertical-align: middle;
          }
          .logo-container {
               width: 25%;
               border: none;
               border-bottom: 0px solid #000;
          }
          .company-info {
               width: 75%;
               text-align: center;
               border: none;
               border-bottom: 1px solid #000;
               padding: 5px;
          }
          .company-name {
               font-size: 20px;
               font-weight: bold;
               margin-bottom: 2px;
          }
          .company-tagline {
               font-size: 12px;
               font-weight: normal;
          }
          .title-row {
               text-align: center;
               font-weight: bold;
               font-size: 13px;
               background-color: #fff;
          }
          .label-cell {
               font-weight: bold;
               width: 15%;
               background-color: #fff;
          }
          .value-cell {
               width: 35%;
          }
          .header-grey {
               background-color: #eee;
               font-weight: bold;
          }
          .right {
               text-align: right;
          }
          .center {
               text-align: center;
          }
          .footer-section {
               margin-top: 20px;
               border: none;
          }
          .net-pay-val {
               font-weight: bold;
               font-size: 14px;
          }
          .rupees-words {
               font-style: italic;
               text-align: center;
          }
          .part-header {
               background-color: #eee;
               font-weight: bold;
               text-align: center;
          }
     </style>
</head>

<body>
     <div class="main-container">
          {{-- Header Section --}}
          <table>
               <tr>
                    <td class="logo-container" style="border-right: none; border-bottom:none; text-align: center;">
                         @php
                              $logoPath = public_path('assets/img/icons/logo-dark.png');
                              $base64Logo = '';
                              if (file_exists($logoPath)) {
                                   $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                                   $data = file_get_contents($logoPath);
                                   $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
                              }
                         @endphp
                         @if($base64Logo)
                              <img src="{{ $base64Logo }}" style="max-height: 60px; max-width: 100%;">
                         @else
                              <div class="company-name">{{ $company_name }}</div>
                         @endif
                    </td>
               </tr>
               <tr>
                    <td class="company-info" style="border-left: none;">
                         <div class="company-tagline">(An Allianze Infosoft Enterprise)</div>
                    </td>
               </tr>
               <tr class="title-row" style="border-bottom:none;">
                    <td>Salary Slip for the month of {{ date("F Y", mktime(0, 0, 0, $entry->batch->month, 1, $entry->batch->year)) }}</td>
               </tr>
          </table>

          {{-- Employee Info Section --}}
          @php
              // Unified filtering for earnings to ensure rows and footers match
               $rawEarnings = $entry->components->where('is_ctc_variable', true)
                    ->merge($entry->components->where('type', 'earning'))
                    ->unique('id')->values();

               // Handle Basic Salary + DA merge
               $earnings = collect();
               $basic = $rawEarnings->first(fn($e) => strtolower($e->component_name) === 'basic salary');
               $da = $rawEarnings->first(fn($e) => strtolower($e->component_name) === 'da');

               if ($basic || $da) {
                    $mergedBasic = clone ($basic ?? $da);
                    if ($basic && $da) {
                         $mergedBasic->standard_amount = $basic->standard_amount + $da->standard_amount;
                         $mergedBasic->amount = $basic->amount + $da->amount;
                    }
                    $mergedBasic->component_name = 'Basic Salary + DA';
                    $earnings->push($mergedBasic);
               }

               foreach ($rawEarnings as $e) {
                    $lowerName = strtolower($e->component_name);
                    if ($lowerName === 'basic salary' || $lowerName === 'da') continue;
                    $earnings->push($e);
               }
               $earnings = $earnings->values();

               $deductions = $entry->components->where('type', 'deduction')->values();
               $employerContribs = $entry->components->where('type', 'employer_contribution')->values();
               $maxRows = max($earnings->count(), $deductions->count(), $employerContribs->count(), 8);

                // Calculate totals for footer from the actual components displayed
               $showRateFn = function($e) {
                    return $e->is_ctc_variable || $e->is_attendance_based || $e->part_number == 1;
               };

               $totalRate = $earnings->filter($showRateFn)->sum('standard_amount');
               $totalEarned = $earnings->sum('amount');
               $totalDeduc = $deductions->sum('amount');
               $totalErContrib = $employerContribs->sum('amount');
               
               // Monthly total CTC for "Permanent CTC" requirement
               $ctcEarningsSum = $earnings->filter(function($c) {
                    $name = strtolower($c->component_name);
                    return str_contains($name, 'basic') || str_contains($name, 'hra') || $name === 'da';
               })->sum('standard_amount');
               $standardErContrib = $employerContribs->sum('standard_amount');
               $permanentCTC = $ctcEarningsSum + $standardErContrib;
          @endphp
          <table>
               <tr>
                    <td class="label-cell" style="border-top:none;">Emp. Code</td>
                    <td class="value-cell" style="border-top:none;">{{ $entry->employee->employeeID }}</td>
                    <td class="label-cell" style="border-top:none;">Employee's Name</td>
                    <td class="value-cell" style="border-top:none;">{{ $entry->employee->full_name }}</td>
               </tr>
               <tr>
                    <td class="label-cell">P.F. No.</td>
                    <td class="value-cell">{{ $entry->employee->pf_no ?? 'N/A' }}</td>
                    <td class="label-cell">ESI No.</td>
                    <td class="value-cell">{{ $entry->employee->esi_no ?? 'N/A' }}</td>
               </tr>
               <tr>
                    <td class="label-cell">PAN No.</td>
                    <td class="value-cell">{{ $entry->employee->pan ?? 'N/A' }}</td>
                    <td class="label-cell">Designation</td>
                    <td class="value-cell">{{ $entry->employee->designation->name ?? 'N/A' }}</td>
               </tr>
               <tr>
                    <td class="label-cell">Date of Joining</td>
                    <td class="value-cell">{{ $entry->employee->join_date ? \Carbon\Carbon::parse($entry->employee->join_date)->format('d-M-y') : 'N/A' }}</td>
                    <td class="label-cell">Department</td>
                    <td class="value-cell">{{ $entry->employee->department->name ?? 'N/A' }}</td>
               </tr>
               <tr>
                    <td class="label-cell">Payable Days</td>
                     <td class="value-cell">{{ $entry->attendance_days > 0 ? number_format($entry->attendance_days, 1) : number_format(count($entry->employee->attendances ?? []) - $entry->lop_days, 1) }}</td>
                    <td class="label-cell">Cost To Company</td>
                    <td class="value-cell">
                         <strong>{{ number_format($permanentCTC, 2) }}</strong>
                    </td>
               </tr>
          </table>

          {{-- Salary Components Section --}}
          <table>
               <thead>
                     <tr class="header-grey">
                          <th style="width: 15%;" style="border-top:none;">Emoluments</th>
                          <th style="width: 8%;" style="border-top:none;">Rate / P.M.</th>
                          <th style="width: 8%;" style="border-top:none;">Rs. / P.M.</th>
                          <th style="width: 12%;" style="border-top:none;">Deductions Employee's</th>
                          <th style="width: 8%;" style="border-top:none;">Rs. / P.M.</th>
                          <th style="width: 12%;" style="border-top:none;">Employer Contribution</th>
                          <th style="width: 8%;" style="border-top:none;">Rs. / P.M.</th>
                          <th style="width: 8%;" style="border-top:none;">Net Pay</th>
                          <th style="width: 8%;" style="border-top:none;">Rs. / P.M.</th>
                     </tr>
               </thead>
               <tbody>
                    @php
                    @endphp

                    @if ($entry->batch->is_part_wise)
                         <tr class="part-header"><td colspan="9">Part - 1</td></tr>
                         @php
                              $earnings1 = collect();
                              $rawEarnings1 = $entry->part1Components->where('is_ctc_variable', true)
                                   ->merge($entry->part1Components->where('type', 'earning'))
                                   ->unique('id')->values();
                              
                              $basic1 = $rawEarnings1->first(fn($e) => strtolower($e->component_name) === 'basic salary');
                              $da1 = $rawEarnings1->first(fn($e) => strtolower($e->component_name) === 'da');
                              
                              if ($basic1 || $da1) {
                                   $merged1 = clone ($basic1 ?? $da1);
                                   if ($basic1 && $da1) {
                                        $merged1->standard_amount = $basic1->standard_amount + $da1->standard_amount;
                                        $merged1->amount = $basic1->amount + $da1->amount;
                                   }
                                   $merged1->component_name = 'Basic Salary + DA';
                                   $earnings1->push($merged1);
                              }
                              foreach($rawEarnings1 as $e) {
                                   $low = strtolower($e->component_name);
                                   if ($low === 'basic salary' || $low === 'da') continue;
                                   $earnings1->push($e);
                              }
                              $earnings1 = $earnings1->values();

                              $deductions1 = $entry->part1Components->where('type', 'deduction')->values();
                               $employerContribs1 = $entry->part1Components->where('type', 'employer_contribution')->values();
                               $maxRows1 = max($earnings1->count(), $deductions1->count(), $employerContribs1->count());
                         @endphp
                         @for ($i = 0; $i < $maxRows1; $i++)
                              <tr>
                                   <td>{{ isset($earnings1[$i]) ? $earnings1[$i]->component_name : '' }}</td>
                                   <td class="right">{{ (isset($earnings1[$i]) && $showRateFn($earnings1[$i])) ? number_format($earnings1[$i]->standard_amount, 2) : '' }}</td>
                                   <td class="right">{{ isset($earnings1[$i]) ? (in_array(strtolower($earnings1[$i]->component_name), ['basic salary + da', 'hra', 'basic salary', 'da']) ? number_format($earnings1[$i]->amount, 0) : number_format($earnings1[$i]->amount, 2)) : '' }}</td>
                                   <td>{{ $deductions1[$i]->component_name ?? '' }}</td>
                                   <td class="right">{{ isset($deductions1[$i]) ? (in_array(strtolower($deductions1[$i]->component_name), ['esi', 'pf']) ? number_format($deductions1[$i]->amount, 0) : number_format($deductions1[$i]->amount, 2)) : '0.00' }}</td>
                                   <td>{{ $employerContribs1[$i]->component_name ?? '' }}</td>
                                   <td class="right">{{ isset($employerContribs1[$i]) ? number_format($employerContribs1[$i]->amount, 2) : '0.00' }}</td>
                                   <td>@if($i == 0) Net Pay (P1) @endif</td>
                                   <td class="right">@if($i == 0) {{ number_format($entry->part1_net, 2) }} @endif</td>
                              </tr>
                         @endfor
                         <tr style="font-weight: bold; background: #f9f9f9;">
                              <td>Total Part 1 Earnings</td>
                                <td class="right">{{ number_format($earnings1->filter($showRateFn)->sum('standard_amount'), 2) }}</td>
                              <td class="right">{{ $earnings1->sum('amount') > 0 ? number_format($earnings1->sum('amount'), 2) : '0.00' }}</td>
                              <td>Total Part 1 Deduc</td>
                              <td class="right">{{ number_format($entry->part1_deductions, 2) }}</td>
                              <td colspan="4"></td>
                         </tr>

                         <tr class="part-header"><td colspan="9">Part - 2</td></tr>
                         @php
                              $earnings2 = collect();
                              $rawEarnings2 = $entry->part2Components->where('is_ctc_variable', true)
                                   ->merge($entry->part2Components->where('type', 'earning'))
                                   ->unique('id')->values();
                              
                              $basic2 = $rawEarnings2->first(fn($e) => strtolower($e->component_name) === 'basic salary');
                              $da2 = $rawEarnings2->first(fn($e) => strtolower($e->component_name) === 'da');
                              
                              if ($basic2 || $da2) {
                                   $merged2 = clone ($basic2 ?? $da2);
                                   if ($basic2 && $da2) {
                                        $merged2->standard_amount = $basic2->standard_amount + $da2->standard_amount;
                                        $merged2->amount = $basic2->amount + $da2->amount;
                                   }
                                   $merged2->component_name = 'Basic Salary + DA';
                                   $earnings2->push($merged2);
                              }
                              foreach($rawEarnings2 as $e) {
                                   $low = strtolower($e->component_name);
                                   if ($low === 'basic salary' || $low === 'da') continue;
                                   $earnings2->push($e);
                              }
                              $earnings2 = $earnings2->values();

                              $deductions2 = $entry->part2Components->where('type', 'deduction')->values();
                               $employerContribs2 = $entry->part2Components->where('type', 'employer_contribution')->values();
                               $maxRows2 = max($earnings2->count(), $deductions2->count(), $employerContribs2->count());
                         @endphp
                         @for ($i = 0; $i < $maxRows2; $i++)
                              <tr>
                                   <td>{{ isset($earnings2[$i]) ? $earnings2[$i]->component_name : '' }}</td>
                                   <td class="right">{{ (isset($earnings2[$i]) && $showRateFn($earnings2[$i])) ? number_format($earnings2[$i]->standard_amount, 2) : '' }}</td>
                                   <td class="right">{{ isset($earnings2[$i]) ? (in_array(strtolower($earnings2[$i]->component_name), ['basic salary + da', 'hra', 'basic salary', 'da']) ? number_format($earnings2[$i]->amount, 0) : number_format($earnings2[$i]->amount, 2)) : '' }}</td>
                                   <td>{{ $deductions2[$i]->component_name ?? '' }}</td>
                                   <td class="right">{{ isset($deductions2[$i]) ? (in_array(strtolower($deductions2[$i]->component_name), ['esi', 'pf']) ? number_format($deductions2[$i]->amount, 0) : number_format($deductions2[$i]->amount, 2)) : '0.00' }}</td>
                                   <td>{{ $employerContribs2[$i]->component_name ?? '' }}</td>
                                   <td class="right">{{ isset($employerContribs2[$i]) ? number_format($employerContribs2[$i]->amount, 2) : '0.00' }}</td>
                                   <td>@if($i == 0) Net Pay (P2) @endif</td>
                                   <td class="right">@if($i == 0) {{ number_format($entry->part2_net, 2) }} @endif</td>
                              </tr>
                         @endfor
                         <tr style="font-weight: bold; background: #f9f9f9;">
                              <td>Total Part 2 Earnings</td>
                                <td class="right">{{ number_format($earnings2->filter($showRateFn)->sum('standard_amount'), 2) }}</td>
                              <td class="right">{{ $earnings2->sum('amount') > 0 ? number_format($earnings2->sum('amount'), 2) : '0.00' }}</td>
                              <td>Total Part 2 Deduc</td>
                              <td class="right">{{ number_format($entry->part2_deductions, 2) }}</td>
                              <td colspan="4"></td>
                         </tr>
                    @else
                         {{-- Regular View: No Part Headers, just one unified list --}}
                         @for ($i = 0; $i < $maxRows; $i++)
                              <tr>
                                   <td>{{ isset($earnings[$i]) ? $earnings[$i]->component_name : '' }}</td>
                                   <td class="right">{{ (isset($earnings[$i]) && $showRateFn($earnings[$i])) ? number_format($earnings[$i]->standard_amount, 2) : '' }}</td>
                                   <td class="right">{{ isset($earnings[$i]) ? (in_array(strtolower($earnings[$i]->component_name), ["basic salary + da", "hra", "basic salary", "da"]) ? number_format($earnings[$i]->amount, 0) : number_format($earnings[$i]->amount, 2)) : "" }}</td>
                                   <td>{{ $deductions[$i]->component_name ?? '' }}</td>
                                   <td class="right">{{ isset($deductions[$i]) ? (in_array(strtolower($deductions[$i]->component_name), ["esi", "pf"]) ? number_format($deductions[$i]->amount, 0) : number_format($deductions[$i]->amount, 2)) : "0.00" }}</td>
                                   <td>{{ $employerContribs[$i]->component_name ?? '' }}</td>
                                   <td class="right">{{ isset($employerContribs[$i]) ? number_format($employerContribs[$i]->amount, 2) : '0.00' }}</td>
                                   <td>@if($i == 0) Net Pay @endif</td>
                                   <td class="right">@if($i == 0) {{ number_format($entry->net_salary, 2) }} @endif</td>
                              </tr>
                         @endfor
                    @endif

                    {{-- Footer Row --}}
                    <tr style="font-weight: bold; background: #fff;">
                         <td>Gross Payable Salary</td>
                         <td class="right">{{ number_format($totalRate, 2) }}</td>
                         <td class="right">{{ number_format($entry->gross_salary, 2) }}</td>
                         <td>Deductions</td>
                         <td class="right">{{ number_format($entry->total_deductions, 2) }}</td>
                          <td>Total Er. Contrib</td>
                          <td class="right">{{ number_format($entry->total_employer_contribution, 2) }}</td>
                         <td>Net Pay</td>
                         <td class="right"><strong>{{ number_format($entry->net_salary, 2) }}</strong></td>
                    </tr>
               </tbody>
          </table>



          {{-- Rupees in Words row --}}
          <table>
               <tr>
                    <td colspan="8" class="rupees-words" style="border-top:none;">
                         @php
                              if (!function_exists('numberToWords')) {
                                   function numberToWords($number) {
                                        $no = (int)floor($number);
                                        $point = (int)round(($number - $no) * 100);
                                        $hundred = null;
                                        $digits_1 = strlen($no);
                                        $i = 0;
                                        $str = array();
                                        $words = array('0' => '', '1' => 'one', '2' => 'two',
                                             '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
                                             '7' => 'seven', '8' => 'eight', '9' => 'nine',
                                             '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
                                             '13' => 'thirteen', '14' => 'fourteen',
                                             '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
                                             '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
                                             '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
                                             '60' => 'sixty', '70' => 'seventy',
                                             '80' => 'eighty', '90' => 'ninety');
                                        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');

                                        while ($i < $digits_1) {
                                             $divider = ($i == 2) ? 10 : 100;
                                             $number = floor($no % $divider);
                                             $no = floor($no / $divider);
                                             $i += ($divider == 10) ? 1 : 2;
                                             if ($number) {
                                                  $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                                                  $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                                                  
                                                  $word = '';
                                                  if ($number < 21) {
                                                      $word = ($words[$number] ?? '') . " " . ($digits[$counter] ?? '') . $plural . " " . $hundred;
                                                  } else {
                                                      $tens = (int)floor($number / 10) * 10;
                                                      $units = $number % 10;
                                                      $word = ($words[$tens] ?? '') . " " . ($words[$units] ?? '') . " " . ($digits[$counter] ?? '') . $plural . " " . $hundred;
                                                  }
                                                  $str[] = $word;
                                             } else $str[] = null;
                                        }
                                        
                                        $result = implode('', array_reverse($str));
                                        $paise = '';
                                        if ($point > 0) {
                                            $tens = (int)floor($point / 10) * 10;
                                            $units = $point % 10;
                                            $paise = "And " . ($words[$tens] ?? '') . " " . ($words[$units] ?? '') . ' Paise';
                                        }
                                        return ($result ? $result : 'zero') . $paise;
                                   }
                               }
                               $words = numberToWords($entry->net_salary);
                          @endphp
                         Rupees in Words: Rupees {{ ucwords($words) }} Only
                    </td>
               </tr>
          </table>

          {{-- Footer Signatures --}}
          <div style="margin-top: 30px; padding: 10px;">
               <div style="float: right; text-align: center;">
                    <br><br>
                    <strong>Executive (HR & Admin)</strong>
               </div>
               <div style="clear: both;"></div>
          </div>
     </div>
</body>

</html>