<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'string|max:255',
            'prenom' => 'string|max:255',
            'nom_societe' => 'string|max:255',
            'tel1' => 'string|max:15',
            'tel2' => 'string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'facebook_page' => 'nullable|string|max:255',
            'instagram_account' => 'nullable|string|max:255',
            'linkedin_page' => 'nullable|string|max:255',
            'site_web' => 'nullable|string|max:255',
            'email' => 'email|max:255',
            'pays_id' => 'exists:countries,id',
            'gouvernerat_id' => 'exists:states,id',
            'adresse' => 'string|max:255',
            'matricul_fiscal' => 'string|max:50',
            'secteur' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'label_id' => 'exists:labels,id',
            'logo' => 'nullable|mimes:png,jpg,jpeg,gif|max:2048',
        ];
    }
}
