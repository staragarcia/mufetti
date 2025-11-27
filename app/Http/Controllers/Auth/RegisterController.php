<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="M01: Authentication",
 *     description="Endpoints for user authentication and registration."
 * )
 */
class RegisterController extends Controller
{
    /**
     * Show registration form.
     *
     * @OA\Get(
     *     path="/register",
     *     summary="Show registration form",
     *     description="Displays the user registration form.",
     *     tags={"M01: Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Registration form displayed successfully"
     *     )
     * )
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register new user.
     *
     * @OA\Post(
     *     path="/register",
     *     summary="Register new user",
     *     description="Creates a new user account and logs in the user.",
     *     tags={"M01: Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="2000-01-01"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123"),
     *             @OA\Property(property="description", type="string", example="Hello, I am John!"),
     *             @OA\Property(property="is_public", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect after successful registration"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'username'    => 'required|string|max:255|unique:users,username',
            'email'       => 'required|email|max:255|unique:users,email',
            'birth_date'  => 'required|date',
            'password'    => 'required|min:6|confirmed',
            'description' => 'nullable|string',
            'is_public'   => 'nullable|boolean',
        ]);

        // Create user
        $user = User::create([
            'name'        => $request->name,
            'username'    => $request->username,
            'email'       => $request->email,
            'password'    => $request->password,  // hashed automatically!
            'birth_date'  => $request->birth_date,
            'description' => $request->description,
            'is_public'   => $request->is_private ? false : true,
            'is_admin'    => false,
        ]);

        // Login the new user
        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('pages.profile.show')
            ->with('success', 'Account created successfully!');
    }
}

