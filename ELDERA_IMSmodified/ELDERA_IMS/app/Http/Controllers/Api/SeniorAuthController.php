<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Senior;
use App\Models\User;
use App\Models\AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SeniorAuthController extends Controller
{
    /**
     * Direct login for senior app users - simplified authentication
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function directLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'osca_id' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Log::info('Direct login attempt for OSCA ID: "' . $request->osca_id . '"');
        
        // Debug all app_users with similar OSCA IDs to help diagnose the issue
        $similarUsers = AppUser::where('osca_id', 'like', '%' . substr($request->osca_id, -3) . '%')->get();
        Log::info('Similar OSCA IDs in database: ' . json_encode($similarUsers->pluck('osca_id')));
        
        // CRITICAL FIX: Make OSCA ID lookup more flexible
        // Try multiple formats to find the user (with/without hyphen, case insensitive)
        $oscaId = $request->osca_id;
        $oscaIdWithoutHyphen = str_replace('-', '', $oscaId);
        $oscaIdWithHyphen = substr($oscaIdWithoutHyphen, 0, 4) . '-' . substr($oscaIdWithoutHyphen, 4);
        
        Log::info('Trying multiple OSCA ID formats', [
            'original' => $oscaId,
            'without_hyphen' => $oscaIdWithoutHyphen,
            'with_hyphen' => $oscaIdWithHyphen
        ]);
        
        // Try all possible formats
        $appUser = AppUser::where('osca_id', $oscaId)
            ->orWhere('osca_id', $oscaIdWithoutHyphen)
            ->orWhere('osca_id', $oscaIdWithHyphen)
            ->first();
        
        if (!$appUser) {
            Log::info('App user not found for OSCA ID: ' . $request->osca_id);
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
        
        // For debugging - log the stored hash length (not the actual hash for security)
        Log::info('Stored password hash length: ' . strlen($appUser->password));
        Log::info('Input password length: ' . strlen($request->password));
        
        // Verify password
        if (!Hash::check($request->password, $appUser->password)) {
            Log::info('Password verification failed for OSCA ID: ' . $request->osca_id);
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }
        
        // Create token only if password is correct
        $token = $appUser->createToken('app_user_token')->plainTextToken;
        
        Log::info('Login successful with proper verification', [
            'osca_id' => $request->osca_id
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $appUser->id,
                'name' => $appUser->first_name . ' ' . $appUser->last_name,
                'email' => $appUser->email,
                'role' => $appUser->role,
                'osca_id' => $appUser->osca_id
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    /**
     * Login senior user and create token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'osca_id' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // First try to find the user in the app_users table
        $appUser = AppUser::where('osca_id', $request->osca_id)->first();
        
        if ($appUser) {
            // Debug the password check
            \Log::info('Login attempt for OSCA ID: ' . $request->osca_id);
            \Log::info('Password check result: ' . (Hash::check($request->password, $appUser->password) ? 'true' : 'false'));
            
            if (Hash::check($request->password, $appUser->password)) {
                // App user found and password matches
                $token = $appUser->createToken('app_user_token')->plainTextToken;
                
                return response()->json([
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $appUser->id,
                        'name' => $appUser->first_name . ' ' . $appUser->last_name,
                        'email' => $appUser->email,
                        'role' => $appUser->role,
                        'osca_id' => $appUser->osca_id
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]);
            } else {
                // Password doesn't match
                return response()->json([
                    'message' => 'Invalid password for app user'
                ], 401);
            }
        }
        
        // If not found in app_users, try the legacy approach with seniors and users tables
        $senior = Senior::where('osca_id', $request->osca_id)->first();
        
        if (!$senior) {
            // Try to find a user with this OSCA ID as email (for backward compatibility)
            $user = User::where('email', $request->osca_id)->first();
            
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid OSCA ID or password'
                ], 401);
            }
            
            // Create a temporary senior record if needed
            if (!$senior) {
                $senior = Senior::where('user_id', $user->id)->first();
                
                if (!$senior) {
                    // This is a user without a senior profile
                    return response()->json([
                        'message' => 'No senior profile found for this account'
                    ], 404);
                }
            }
        } else {
            // Get the user associated with this senior
            $user = User::find($senior->user_id);
            
            if (!$user || !Hash::check($request->password, $user->password)) {
                // Try direct login with OSCA ID as username
                $user = User::where('email', $request->osca_id)->first();
                
                if (!$user || !Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'message' => 'Invalid login credentials'
                    ], 401);
                }
            }
        }
        
        // Check if user is a senior or admin (allow both for testing)
        if (!in_array($user->role, ['senior', 'admin'])) {
            Auth::logout();
            return response()->json([
                'message' => 'This account is not authorized for mobile app access'
            ], 403);
        }

        // Get associated senior profile
        $senior = Senior::where('user_id', $user->id)->first();
        
        if (!$senior) {
            Auth::logout();
            return response()->json([
                'message' => 'No senior profile found for this account'
            ], 404);
        }

        // Create token
        $token = $user->createToken('senior_auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $senior->first_name . ' ' . $senior->last_name,
                'email' => $user->email,
                'role' => $user->role,
                'senior_id' => $senior->id,
                'osca_id' => $senior->osca_id
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Get senior profile data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        // Check if the user is an AppUser
        if ($user instanceof AppUser) {
            // Resolve Senior profile using shared OSCA ID
            $senior = Senior::where('osca_id', $user->osca_id)->first();

            if ($senior) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $senior->id,
                        'osca_id' => $senior->osca_id,
                        'name' => $senior->first_name . ' ' . $senior->last_name,
                        'first_name' => $senior->first_name,
                        'last_name' => $senior->last_name,
                        'middle_name' => $senior->middle_name,
                        'name_extension' => $senior->name_extension,
                        'date_of_birth' => $senior->date_of_birth,
                        'age' => $senior->age,
                        'sex' => $senior->sex,
                        'contact_number' => $senior->contact_number,
                        'email' => $user->email,
                        'address' => [
                            'region' => $senior->region,
                            'province' => $senior->province,
                            'city' => $senior->city,
                            'barangay' => $senior->barangay,
                            'residence' => $senior->residence,
                            'street' => $senior->street,
                        ],
                        'has_pension' => $senior->has_pension,
                        'status' => $senior->status,
                        'photo_path' => $senior->photo_path,
                    ]
                ]);
            }

            // Fallback to minimal AppUser data if no senior record is found
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'osca_id' => $user->osca_id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ]);
        }
        
        // Legacy approach for User model
        // Get associated senior profile
        $senior = Senior::where('user_id', $user->id)->first();
        
        if (!$senior) {
            return response()->json([
                'message' => 'No senior profile found for this account'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $senior->id,
                'osca_id' => $senior->osca_id,
                'name' => $senior->first_name . ' ' . $senior->last_name,
                'first_name' => $senior->first_name,
                'last_name' => $senior->last_name,
                'middle_name' => $senior->middle_name,
                'name_extension' => $senior->name_extension,
                'date_of_birth' => $senior->date_of_birth,
                'age' => $senior->age,
                'sex' => $senior->sex,
                'contact_number' => $senior->contact_number,
                'email' => $user->email,
                'address' => [
                    'region' => $senior->region,
                    'province' => $senior->province,
                    'city' => $senior->city,
                    'barangay' => $senior->barangay,
                    'residence' => $senior->residence,
                    'street' => $senior->street,
                ],
                'has_pension' => $senior->has_pension,
                'status' => $senior->status,
                'photo_path' => $senior->photo_path,
            ]
        ]);
    }

    /**
     * Logout user (revoke the token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
    
    /**
     * Register a new app user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'osca_id' => 'required|string|unique:app_users,osca_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create new app user
        $appUser = AppUser::create([
            'osca_id' => $request->osca_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'senior',
        ]);

        // Create token
        $token = $appUser->createToken('app_user_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'id' => $appUser->id,
                'name' => $appUser->first_name . ' ' . $appUser->last_name,
                'email' => $appUser->email,
                'role' => $appUser->role,
                'osca_id' => $appUser->osca_id
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}