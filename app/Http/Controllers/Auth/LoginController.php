<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="M01: Authentication",
 *     description="Endpoints for user authentication and login."
 * )
 */
class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @OA\Get(
     *     path="/login",
     *     summary="Show login form",
     *     description="Display the login form. Redirects to profile if user is already authenticated.",
     *     tags={"M01: Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Login form displayed successfully"
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to profile if already authenticated"
     *     )
     * )
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('profile.show');
        } else {
            return view('auth.login');
        }
    }

    /**
     * Process an authentication attempt.
     *
     * @OA\Post(
     *     path="/login",
     *     summary="Authenticate user",
     *     description="Validates credentials and logs in the user. Returns redirect to profile on success.",
     *     tags={"M01: Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="remember", type="boolean", description="Remember me option", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect after successful authentication"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed or authentication error"
     *     )
     * )
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // Validate the request data.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate and log in the user.
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate the session ID to prevent session fixation attacks.
            $request->session()->regenerate();

            // Redirect the user to their intended destination (default: profile.show).
            return redirect()->intended(route('profile.show'));
        }

        // Authentication failed: return back with an error message.
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}

