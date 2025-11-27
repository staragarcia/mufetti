<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="M01: Authentication",
 *     description="Endpoints for user authentication, registration, and logout."
 * )
 */
class LogoutController extends Controller
{
    /**
     * Log the user out of the application.
     *
     * @OA\Post(
     *     path="/logout",
     *     summary="Logout authenticated user",
     *     description="Logs out the current user, invalidates the session, regenerates the CSRF token, and redirects to login page.",
     *     tags={"M01: Authentication"},
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to login page after successful logout"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        // Log out the authenticated user.
        Auth::logout();

        // Invalidate the current session to prevent session fixation or reuse.
        $request->session()->invalidate();

        // Regenerate the CSRF token for added security.
        $request->session()->regenerateToken();

        // Redirect to login route with a success flash message.
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');
    }
}

