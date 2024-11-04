<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {
        return 'reg';
    }
    public function login(Request $request) {
        return 'in';
    }
    public function logout(Request $request) {
        return 'out';
    }
}
