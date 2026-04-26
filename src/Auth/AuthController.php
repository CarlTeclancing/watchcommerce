<?php

declare(strict_types=1);

namespace App\Auth;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Http\Validator;
use App\Infrastructure\Security\Auth;
use App\Infrastructure\Security\Csrf;
use App\Infrastructure\Security\Password;

final class AuthController extends Controller
{
    public function showRegister(Request $request): Response
    {
        /** @var Csrf $csrf */
        $csrf = $this->container->get(Csrf::class);
        return $this->view('auth/register', ['csrf' => $csrf->token()]);
    }

    public function register(Request $request): Response
    {
        $this->verifyCsrf($request);

        [$data, $errors] = Validator::validate($request->post, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($errors !== []) {
            return $this->json(['errors' => $errors], 422);
        }

        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $statement = $db->pdo()->prepare('INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)');
        $statement->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => Password::hash($data['password']),
        ]);

        return $this->redirect('/login');
    }

    public function showLogin(Request $request): Response
    {
        /** @var Csrf $csrf */
        $csrf = $this->container->get(Csrf::class);
        return $this->view('auth/login', ['csrf' => $csrf->token()]);
    }

    public function login(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Auth $auth */
        $auth = $this->container->get(Auth::class);

        if (!$auth->attempt((string)$request->input('email'), (string)$request->input('password'))) {
            return $this->json(['message' => 'Invalid credentials.'], 401);
        }

        if ($auth->requiresTwoFactor()) {
            return $this->redirect('/2fa');
        }

        return $this->redirect('/');
    }

    public function showTwoFactor(Request $request): Response
    {
        /** @var Csrf $csrf */
        $csrf = $this->container->get(Csrf::class);
        return $this->view('auth/two_factor', ['csrf' => $csrf->token()]);
    }

    public function verifyTwoFactor(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Auth $auth */
        $auth = $this->container->get(Auth::class);

        if (!$auth->verifyTwoFactorCode((string)$request->input('code'))) {
            return $this->json(['message' => 'Invalid 2FA code.'], 422);
        }

        return $this->redirect('/admin');
    }

    public function logout(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Auth $auth */
        $auth = $this->container->get(Auth::class);
        $auth->logout();
        return $this->redirect('/');
    }
}
