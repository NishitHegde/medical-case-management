<?php

namespace App\Http\Requests;

use App\Models\MedicalCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $case = $this->route('case') ?? $this->route('id');
        if (is_numeric($case)) {
            $case = MedicalCase::find($case);
        }

        return $case ? $this->user()->can('update', $case) : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $case = $this->route('case') ?? $this->route('id');
        $caseId = $case instanceof MedicalCase ? $case->id : $case;

        return [
            'case_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('cases', 'case_number')->ignore($caseId),
            ],
            'patient_reference' => [
                'required',
                'string',
                'max:100',
            ],
            'surgeon_name' => [
                'required',
                'string',
                'max:150',
            ],
            'implant_type' => [
                'required',
                'string',
                'max:150',
            ],
            'status' => [
                'required',
                Rule::in(MedicalCase::STATUSES),
            ],
            'priority' => [
                'required',
                Rule::in(MedicalCase::PRIORITIES),
            ],
            'surgery_date' => [
                'nullable',
                'date',
            ],
        ];
    }

    /**
     * Custom message definitions.
     */
    public function messages(): array
    {
        return [
            'case_number.unique' => 'A case with this Case Number already exists.',
            'status.in' => 'The selected status is invalid. Allowed statuses: Draft, Planning, In Review, Approved, Completed, Cancelled.',
            'priority.in' => 'The selected priority is invalid. Allowed priorities: Low, Medium, High.',
        ];
    }
}
