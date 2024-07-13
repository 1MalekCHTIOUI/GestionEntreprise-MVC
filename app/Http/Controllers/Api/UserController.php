<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{


    public function __construct()
{
    $this->middleware('permission:employee-list|employee-create|employee-edit|employee-delete', ['only' => ['index']]);
    $this->middleware('permission:employee-create', ['only' => ['create', 'store']]);
    $this->middleware('permission:employee-edit', ['only' => ['edit', 'update']]);
    $this->middleware('permission:employee-delete', ['only' => ['destroy']]);
}


    public function index()
    {
        $users = User::latest()->paginate(10);
        return response()->json($users);
    }



    public function store(StoreUserRequest $request): JsonResponse
    {
        $authenticatedUser = Auth::user();
        if (!$authenticatedUser) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 401);
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        // Vérifier si le rôle est spécifié dans la demande
        if ($request->has('role')) {
            // Trouver le rôle par son nom
            $role = Role::where('name', $request->input('role'))->first();

            if ($role) {
                // Assigner le rôle à l'utilisateur créé
                $user->assignRole($role);
            } else {
                // Retourner une réponse si le rôle spécifié n'existe pas
                return response()->json([
                    'success' => false,
                    'message' => 'Le rôle spécifié n\'existe pas.'
                ], 404);
            }
        } else {
            // Retourner une réponse si aucun rôle n'est spécifié
            return response()->json([
                'success' => false,
                'message' => 'Le champ de rôle est requis.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès.',
            'user' => $user
        ], 201);
    }



    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }



    // public function edit(User $user): JsonResponse
    // {
    //     $roles = Role::pluck('name', 'name')->all();
    //     $userRoles = $user->roles->pluck('name', 'name')->all();
    //     return response()->json([
    //         'user' => $user,
    //         'roles' => $roles,
    //         'userRoles' => $userRoles
    //     ]);
    // }
    public function update(UpdateUserRequest $request, User $user)
    {
        // Utiliser les données validées à partir de la demande
        $validatedData = $request->validated();

        // Mettre à jour l'utilisateur avec les données validées
        $user->update($validatedData);

        // Retourner l'utilisateur mis à jour
        return response()->json([
            'success' => true,
            'message' => 'Utilisateur mis à jour avec succès.',
            'user' => $user
        ]);
    }



    public function destroy(User $user)
    {
        $authenticatedUser = Auth::user();
        if (!$authenticatedUser) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
}
