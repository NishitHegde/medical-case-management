<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCaseRequest;
use App\Http\Requests\UpdateCaseRequest;
use App\Models\MedicalCase;
use App\Services\HolidayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaseController extends Controller
{
    /**
     * Display a listing of the medical cases.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', MedicalCase::class);

        $query = MedicalCase::with('creator');

        // Search by case number
        if ($request->filled('case_number')) {
            $query->where('case_number', 'like', '%' . trim($request->case_number) . '%');
        }

        // Search by surgeon name
        if ($request->filled('surgeon_name')) {
            $query->where('surgeon_name', 'like', '%' . trim($request->surgeon_name) . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority (bonus)
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Sorting (bonus)
        $allowedSortColumns = ['case_number', 'patient_reference', 'surgeon_name', 'status', 'priority', 'surgery_date', 'created_at'];
        $sortColumn = in_array($request->sort, $allowedSortColumns) ? $request->sort : 'created_at';
        $sortDirection = in_array(strtolower($request->direction), ['asc', 'desc']) ? strtolower($request->direction) : 'desc';

        $query->orderBy($sortColumn, $sortDirection);

        // 10 cases per page with preserved query parameters
        $cases = $query->paginate(10)->withQueryString();

        $statuses = MedicalCase::STATUSES;
        $priorities = MedicalCase::PRIORITIES;

        return view('cases.index', compact('cases', 'statuses', 'priorities', 'sortColumn', 'sortDirection'));
    }

    /**
     * Show the form for creating a new case.
     */
    public function create(): View
    {
        $this->authorize('create', MedicalCase::class);

        $case = new MedicalCase();
        $statuses = MedicalCase::STATUSES;
        $priorities = MedicalCase::PRIORITIES;

        return view('cases.create', compact('case', 'statuses', 'priorities'));
    }

    /**
     * Store a newly created case in storage.
     */
    public function store(StoreCaseRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = $request->user()->id;

        $case = MedicalCase::create($validated);

        return redirect()->route('cases.index')
            ->with('success', "Medical case [{$case->case_number}] created successfully.");
    }

    /**
     * Display the specified medical case.
     */
    public function show(MedicalCase $case, HolidayService $holidayService): View
    {
        $this->authorize('view', $case);

        $case->load('creator');

        $surgeryHoliday = $case->surgery_date
            ? $holidayService->checkHoliday($case->surgery_date)
            : null;

        return view('cases.show', compact('case', 'surgeryHoliday'));
    }

    /**
     * Show the form for editing the specified case.
     */
    public function edit(MedicalCase $case): View
    {
        $this->authorize('update', $case);

        $statuses = MedicalCase::STATUSES;
        $priorities = MedicalCase::PRIORITIES;

        return view('cases.edit', compact('case', 'statuses', 'priorities'));
    }

    /**
     * Update the specified medical case in storage.
     */
    public function update(UpdateCaseRequest $request, MedicalCase $case): RedirectResponse
    {
        $validated = $request->validated();
        $case->update($validated);

        return redirect()->route('cases.show', $case)
            ->with('success', "Medical case [{$case->case_number}] updated successfully.");
    }

    /**
     * Remove the specified medical case from storage.
     */
    public function destroy(MedicalCase $case): RedirectResponse
    {
        $this->authorize('delete', $case);

        $caseNumber = $case->case_number;
        $case->delete();

        return redirect()->route('cases.index')
            ->with('success', "Medical case [{$caseNumber}] deleted successfully.");
    }
}
