<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'nom_societe' => 'required|string|max:255',
            'tel1' => 'required|string|max:15',
            'tel2' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'facebook_page' => 'nullable|string|max:255',
            'instagram_account' => 'nullable|string|max:255',
            'linkedin_page' => 'nullable|string|max:255',
            'site_web' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'pays_id' => 'required|exists:countries,id',
            'gouvernerat_id' => 'required|exists:states,id',
            'adresse' => 'required|string|max:255',
            'matricul_fiscal' => 'required|string|max:50',
            'secteur' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'label_id' => 'required|exists:labels,id',
            'logo' => 'nullable|string|max:255',
        ];
    }
}
