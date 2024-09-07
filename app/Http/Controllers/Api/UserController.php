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
use Log;
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
        $users = User::with('roles')->latest()->get();

        $transformedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                // 'roles' => $user->roles->pluck('name')->toArray(),
                'roles' => $user->roles->toArray(),
            ];
        });

        return response()->json($transformedUsers);
    }
    public function store(StoreUserRequest $request): JsonResponse
    {
        $authenticatedUser = Auth::user();
        $request->validated();
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
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {


        try {
            $validatedData = $request->validated();
            Log::info($validatedData);
            // Update user data
            $user->name = $validatedData['name'] ?? $user->name;
            $user->email = $validatedData['email'] ?? $user->email;

            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            // Save the users
            $user->save();

            // Update role if provided
            if (isset($validatedData['role'])) {
                Log::info('Role: ', [$validatedData['role']]);
                $role = Role::where('name', "=", $validatedData['role'])->first();
                if ($role) {
                    $user->roles()->sync([$role->id]);
                } else {
                    throw new \Exception('Role not found');
                }
            }

            return response()->json(['message' => 'User updated successfully']);
        } catch (\Exception $e) {
            // Log the detailed error message
            dd('User Update Error: ', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'validatedData' => $validatedData ?? null,
                'user' => $user->toArray(),
            ]);

            // Return a JSON response with the error message
            return response()->json(['message' => 'An error occurred while updating the user.'], 500);
        }
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
