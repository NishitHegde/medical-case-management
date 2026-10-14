<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCaseRequest;
use App\Http\Requests\UpdateCaseRequest;
use App\Http\Resources\CaseResource;
use App\Models\MedicalCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CaseApiController extends Controller
{
    /**
     * Display a paginated listing of cases.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', MedicalCase::class);

        $query = MedicalCase::with('creator');

        if ($request->filled('case_number')) {
            $query->where('case_number', 'like', '%' . $request->case_number . '%');
        }

        if ($request->filled('surgeon_name')) {
            $query->where('surgeon_name', 'like', '%' . $request->surgeon_name . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $perPage = (int) $request->get('per_page', 10);
        $cases = $query->latest('id')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => CaseResource::collection($cases),
            'pagination' => [
                'current_page' => $cases->currentPage(),
                'last_page' => $cases->lastPage(),
                'per_page' => $cases->perPage(),
                'total' => $cases->total(),
            ],
            'message' => 'Cases retrieved successfully',
        ], 200);
    }

    /**
     * Store a newly created case in storage.
     */
    public function store(StoreCaseRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = $request->user()->id;

        $case = MedicalCase::create($validated);
        $case->load('creator');

        return response()->json([
            'success' => true,
            'data' => new CaseResource($case),
            'message' => 'Case created successfully',
        ], 201);
    }

    /**
     * Display the specified case.
     */
    public function show(int $id): JsonResponse
    {
        $case = MedicalCase::with('creator')->find($id);

        if (!$case) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Case not found',
            ], 404);
        }

        $this->authorize('view', $case);

        return response()->json([
            'success' => true,
            'data' => new CaseResource($case),
            'message' => 'Case retrieved successfully',
        ], 200);
    }

    /**
     * Update the specified case in storage.
     */
    public function update(UpdateCaseRequest $request, int $id): JsonResponse
    {
        $case = MedicalCase::find($id);

        if (!$case) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Case not found',
            ], 404);
        }

        $this->authorize('update', $case);

        $validated = $request->validated();
        $case->update($validated);
        $case->load('creator');

        return response()->json([
            'success' => true,
            'data' => new CaseResource($case),
            'message' => 'Case updated successfully',
        ], 200);
    }

    /**
     * Remove the specified case from storage.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $case = MedicalCase::find($id);

        if (!$case) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Case not found',
            ], 404);
        }

        $this->authorize('delete', $case);

        $case->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Case deleted successfully',
        ], 200);
    }
}
