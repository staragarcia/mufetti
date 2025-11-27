<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="M01: Authentication",
 *     description="Endpoints for user authentication, registration, and password management."
 * )
 */
class ForgotPasswordController extends Controller
{
    /**
     * Show the form to request a password reset.
     *
     * @OA\Get(
     *     path="/password/forgot",
     *     summary="Show forgot password form",
     *     description="Displays the password reset request form.",
     *     tags={"M01: Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Returns the forgot password form view"
     *     )
     * )
     */
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle sending the password reset link.
     *
     * @OA\Post(
     *     path="/password/forgot",
     *     summary="Send password reset link",
     *     description="Validates the email and simulates sending a password reset link.",
     *     tags={"M01: Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Redirects back with a status message"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function sendReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Não precisa enviar email — só mostrar success
        return back()->with('status', 'If the email exists, a reset link was sent.');
    }
}

