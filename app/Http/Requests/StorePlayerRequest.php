<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StorePlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        
        // Check if user is logged in and has the scout or admin role
        return auth()->check() && $user instanceof User && in_array($user->role, ['scout', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'nationality' => 'required|string|max:255',
            'team_id' => 'nullable|exists:teams,id',
            'position_id' => 'nullable|exists:positions,id',
            'height' => 'nullable|integer|min:140|max:220',
            'weight' => 'nullable|integer|min:40|max:130',
            'preferred_foot' => 'required|in:right,left,both',
            'jersey_number' => 'nullable|integer|min:1|max:99',
            'bio' => 'nullable|string',
            'market_value' => 'nullable|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}