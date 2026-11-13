<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine whether the current user can
     * submit a comment.
     *
     * Only authenticated users are allowed.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Define the validation rules for comments.
     *
     * required: Comment cannot be empty.
     * string: Comment must contain text.
     * min:3: At least 3 characters.
     * max:1000: No more than 1000 characters.
     */
    public function rules(): array
    {
        return [
            'body' => [
                'required',
                'string',
                'min:3',
                'max:1000',
            ],
        ];
    }

    /**
     * Provide customized validation messages
     * for the Barangay Bulletin application.
     */
    public function messages(): array
    {
        return [
            'body.required' => 'Please enter your comment.',
            'body.string' => 'Your comment must contain text.',
            'body.min' => 'Purok Dimatulac comments need at least 3 characters.',
            'body.max' => 'Your comment cannot exceed 1000 characters.',
        ];
    }
}
