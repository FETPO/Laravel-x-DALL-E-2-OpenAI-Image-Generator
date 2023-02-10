<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class OpenaiController
{
    public function generateImage(Request $request)
    {
        $prompt = $request->get('prompt');
        $size = $request->get('size');
        $n = $request->get('n');

        $imageSize = $size === 'small' ? '256x256' : ($size === 'medium' ? '512x512' : '1024x1024');

        $response = OpenAI::images()->create([
            'prompt' => $prompt,
            'n'      => (int)$n,
            'size'   => $imageSize
        ]);

        $images = collect($response->data)->pluck('url');

        return response()->json([
            'success' => true,
            'data'    => $images,
        ]);
    }
}