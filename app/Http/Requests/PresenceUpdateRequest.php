<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresenceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required'],
            'shift_store_id' => ['required'],
            'status' => ['required'],
            'image_in' => ['nullable', 'string'],
            'start_date_time' => ['required', 'date'],
            'latitude_in' => ['required'],
            'longitude_in' => ['required'],
            'image_out' => ['nullable', 'string'],
            'end_date_time' => ['nullable', 'date'],
            'latitude_out' => ['nullable'],
            'longitude_out' => ['nullable'],
            'created_by_id' => ['nullable'],
            'approved_by_id' => ['nullable'],
        ];
    }
}
