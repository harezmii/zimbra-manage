<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LdapController extends Controller
{
    protected $ldapService;

    public function __construct(LdapService $ldapService)
    {
        $this->ldapService = $ldapService;
    }

    public function checkConnection()
    {
        $isConnected = $this->ldapService->checkConnection();

        if ($isConnected) {
            return response()->json(['message' => 'Connected to LDAP server.'], 200);
        } else {
            return response()->json(['message' => 'Failed to connect to LDAP server.'], 500);
        }
    }
    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'samaccountname' => 'required|string',
            'givenname' => 'required|string',
            'sn' => 'required|string',
            'mail' => 'required|email',
        ]);
        $this->ldapService->createUser($validated);

        return response()->json(['message' => 'User created successfully.'], 201);
    }

    public function searchUser($username)
    {
        $user = $this->ldapService->searchUser($username);

        if ($user) {
            return response()->json($user);
        }

        return response()->json(['message' => 'User not found.'], 404);
    }

    public function deleteUser($username)
    {
        $result = $this->ldapService->deleteUser($username);

        if ($result) {
            return response()->json(['message' => 'User deleted successfully.']);
        }

        return response()->json(['message' => 'User not found.'], 404);
    }

    public function recentlyCreatedUsers()
    {
        $users = $this->ldapService->recentlyCreatedUsers();

        return response()->json($users);
    }
}
