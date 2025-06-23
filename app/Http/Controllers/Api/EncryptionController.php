<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptionController extends Controller
{
    private const CIPHER = 'aes-256-cbc';

    private function getKey(Request $request): string
    {
        return hash('sha256', $request->bearerToken());
    }

    public function encrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CIPHER));
        $encrypted = openssl_encrypt(json_encode($request->data), self::CIPHER, $this->getKey($request), 0, $iv);

        if ($encrypted === false) {
            return response()->json(['error' => 'Could not encrypt data.'], 500);
        }

        return response()->json([
            'data' => base64_encode($iv . $encrypted)
        ]);
    }

    public function decrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $payload = base64_decode($request->data);
        $iv_length = openssl_cipher_iv_length(self::CIPHER);
        $iv = substr($payload, 0, $iv_length);
        $encrypted = substr($payload, $iv_length);

        $decrypted = openssl_decrypt($encrypted, self::CIPHER, $this->getKey($request), 0, $iv);

        if ($decrypted === false) {
             throw new DecryptException('Could not decrypt the data.');
        }

        return response()->json(json_decode($decrypted, true));
    }
}
