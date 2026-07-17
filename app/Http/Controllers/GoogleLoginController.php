<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\GoogleTokenVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class GoogleLoginController extends Controller
{
    public function authenticate(Request $request, GoogleTokenVerifier $tokenVerifier): JsonResponse
    {
        $validated = $request->validate([
            'credential' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        if (trim((string) config('services.google.client_id')) === '') {
            return response()->json([
                'message' => 'Login com Google indisponivel no momento.',
            ], 503);
        }

        try {
            $payload = $tokenVerifier->verify($validated['credential']);
        } catch (Throwable $exception) {
            Log::warning('Falha ao validar token do Google.', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'Nao foi possivel validar o login com Google.',
            ], 422);
        }

        $googleId = trim((string) ($payload['sub'] ?? ''));
        $email = strtolower(trim((string) ($payload['email'] ?? '')));
        $name = trim((string) ($payload['name'] ?? ''));
        $avatar = trim((string) ($payload['picture'] ?? ''));
        $emailVerified = filter_var($payload['email_verified'] ?? false, FILTER_VALIDATE_BOOL);

        if ($googleId === '' || $email === '' || ! $emailVerified) {
            return response()->json([
                'message' => 'Sua conta Google precisa fornecer um e-mail verificado.',
            ], 422);
        }

        $userByGoogle = User::where('google_id', $googleId)->first();
        $userByEmail = $userByGoogle ? null : User::where('email', $email)->first();

        if ($userByEmail && filled($userByEmail->google_id) && $userByEmail->google_id !== $googleId) {
            return response()->json([
                'message' => 'Este e-mail ja esta vinculado a outra conta Google.',
            ], 409);
        }

        $ownerEmail = trim((string) config('auth.owner_email', 'admin@studio.com'));
        $isOwnerEmail = $ownerEmail !== '' && strcasecmp($email, $ownerEmail) === 0;

        $user = DB::transaction(function () use ($avatar, $email, $googleId, $isOwnerEmail, $name, $userByEmail, $userByGoogle) {
            $user = $userByGoogle ?? $userByEmail;

            if (! $user) {
                return User::create([
                    'name' => $name !== '' ? $name : Str::before($email, '@'),
                    'email' => $email,
                    'password' => Hash::make(Str::random(40)),
                    'role' => $isOwnerEmail ? User::ROLE_ADMIN : User::ROLE_CLIENTE,
                    'email_verified_at' => now(),
                    'google_id' => $googleId,
                    'google_avatar' => $avatar !== '' ? $avatar : null,
                ]);
            }

            $updates = [];

            if (! filled($user->google_id)) {
                $updates['google_id'] = $googleId;
            }

            if ($avatar !== '' && $user->google_avatar !== $avatar) {
                $updates['google_avatar'] = $avatar;
            }

            if ($user->email_verified_at === null) {
                $updates['email_verified_at'] = now();
            }

            if ($isOwnerEmail && $user->role !== User::ROLE_ADMIN) {
                $updates['role'] = User::ROLE_ADMIN;
            }

            if ($updates !== []) {
                $user->forceFill($updates)->save();
            }

            return $user->refresh();
        });

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return response()->json([
            'redirect' => redirect()->intended(
                $user->can('access-admin-area') ? route('admin.dashboard') : route('agendamento.create')
            )->getTargetUrl(),
        ]);
    }
}