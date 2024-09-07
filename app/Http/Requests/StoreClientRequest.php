<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'string|max:255',
            'prenom' => 'string|max:255',
            'nom_societe' => 'string|max:255',
            'tel1' => 'string|max:15',
            'tel2' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',  // Validation rule for whatsapp
            'facebook_page' => 'nullable|string|max:255',
            'instagram_account' => 'nullable|string|max:255',
            'linkedin_page' => 'nullable|string|max:255',
            'site_web' => 'nullable|string|max:255',
            'email' => 'email|max:255',
            'pays_id' => 'required|exists:countries,id',
            'gouvernerat_id' => 'required|exists:states,id',
            'adresse' => 'required|string|max:255',
            'matricul_fiscal' => 'required|string|max:50',
            'secteur' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'label_id' => 'required|exists:labels,id',
            'logo' => 'nullable|mimes:png,jpg,jpeg,gif|max:2048',
        ];
    }

}
