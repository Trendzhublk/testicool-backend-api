<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:addresses,id'],
            'ratings' => ['required', 'array', 'min:1'],
            'ratings.*' => ['numeric', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $addressId = $validated['order_id'];

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('feedback', $this->disk());
        }

        $feedback = Feedback::create([
            'address_id' => $addressId,
            'ratings' => $validated['ratings'],
            'comment' => $validated['comment'] ?? null,
            'image_path' => $path,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Feedback received. Thank you!',
            'id' => $feedback->id,
        ], 201);
    }

    private function disk(): string
    {
        // Prefer S3 if configured, else public
        $default = config('filesystems.default', 'public');
        return $default ?: 'public';
    }
}
