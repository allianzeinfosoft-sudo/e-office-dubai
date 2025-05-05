<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CompanyPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyPolicyController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $policies = CompanyPolicy::orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Company Policies fetched successfully',
                'data' => $policies->map(function ($policy, $index) {
                    return [
                        'row'               => $index + 1,
                        'id'                => $policy->id,
                        'policyTitle'       => $policy->policyTitle ?? '',
                        'policyDescription' => $policy->policyDescription ?? '',
                        'policyStartDate'   => date('d-m-Y', strtotime($policy->policyStartDate)),
                        'status'            => $policy->status ?? '',
                        'attachments'       => $policy->attachments ?? '',
                        'createdAt'         => $policy->created_at->format('d-m-Y'),
                    ];
                }),
            ]);
        }

        $data['meta_title'] = 'Company Policies';
        return view('company.policies.index', $data);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'id'                => 'nullable|integer',
            'policyTitle'       => 'required|string|max:255',
            'policyStartDate'   => 'required|date',
            'policyDescription' => 'nullable|string',
            'attachments'       => 'nullable|file|mimes:pdf,docx,jpg,jpeg,png|max:10240',
        ]);

        $validatedData = $validated;
        $validatedData['policyStartDate'] = Carbon::parse($validated['policyStartDate'])->format('Y-m-d');

        if ($request->hasFile('attachments')) {
            if ($request->has('id')) {
                $existingPolicy = CompanyPolicy::find($request->id);
                if ($existingPolicy && $existingPolicy->attachments) {
                    $oldFile = 'public/company_policies/' . $existingPolicy->attachments;
                    if (Storage::exists($oldFile)) {
                        Storage::delete($oldFile);
                    }
                }
            }

            $file = $request->file('attachments');
            $fileName = uniqid('company_policy_', true) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/company_policies', $fileName);
            $validatedData['attachments'] = $fileName;

        } elseif ($request->filled('id')) {
            $validatedData['attachments'] = CompanyPolicy::find($request->id)->attachments ?? null;
        }

        $policy = CompanyPolicy::updateOrCreate(
            ['id' => $validatedData['id'] ?? null],
            [
                'policyTitle'       => $validatedData['policyTitle'] ?? '',
                'policyStartDate'   => $validatedData['policyStartDate'] ?? '',
                'policyDescription' => $validatedData['policyDescription'] ?? '',
                'attachments'       => $validatedData['attachments'] ?? '',
            ]
        );

        return response()->json([
            'message' => 'Company Policy saved successfully!',
            'data' => $policy
        ]);
    }

    public function show(CompanyPolicy $companyPolicy){
        $data['companyPolicy'] = $companyPolicy;
        $data['meta_title'] = $companyPolicy->policyTitle;
        $html = view('company.policies.show', $data)->render();
        //return view('others.moms.show', $data);
        return response()->json([
            'message' => 'MOM fetched successfully',
            'html' => $html,
            'meta_title' => $companyPolicy->policyTitle,
        ]);
    }

    public function edit(CompanyPolicy $companyPolicy){
        $data['policy'] = $companyPolicy;
        $data['meta_title'] = 'Edit Company Policy';
        return response()->json($data);
    }

    public function destroy(CompanyPolicy $companyPolicy){
        if ($companyPolicy->attachments && Storage::exists('public/company_policies/' . $companyPolicy->attachments)) {
            Storage::delete('public/company_policies/' . $companyPolicy->attachments);
        }

        $companyPolicy->delete();

        return response()->json(['message' => 'Company Policy deleted successfully']);
    }

    public function markAsRead(CompanyPolicy $companyPolicy){
        $companyPolicy->update(['status' => 1]);
        return response()->json(['message' => 'Company Policy marked as read successfully']);
    }
}
