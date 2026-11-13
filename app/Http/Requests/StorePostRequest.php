<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    /**
     * Determine whether the logged-in user
     * is allowed to create an announcement.
     *
     * Only accounts linked to an author profile
     * can publish barangay announcements.
     */
    public function authorize(): bool
    {
        return $this->user() !== null
            && $this->user()->author()->exists();
    }

    /**
     * Define the validation rules for
     * creating a new bulletin announcement.
     */
    public function rules(): array
    {
        return [
            // Announcement title is required.
            'title' => [
                'required',
                'string',
                'max:120',
            ],

            // Announcement body must contain
            // at least 20 characters.
            'body' => [
                'required',
                'string',
                'min:20',
            ],

            // Restrict categories to the five
            // predefined barangay categories.
            'category' => [
                'required',
                Rule::in([
                    'Announcement',
                    'Health',
                    'Peace & Order',
                    'Ayuda',
                    'Events',
                ]),
            ],

            // Require at least one tag.
            'tags' => [
                'required',
                'array',
                'min:1',
            ],

            // Each selected tag ID must exist
            // in the tags database table.
            'tags.*' => [
                'integer',
                'distinct',
                'exists:tags,id',
            ],

            // Accept a boolean publish option.
            'is_published' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    /**
     * Customize validation feedback.
     */
    public function messages(): array
    {
        return [
            'title.required' =>
                'Please enter an announcement title.',

            'title.max' =>
                'The title cannot exceed 120 characters.',

            'body.required' =>
                'Please enter the announcement details.',

            'body.min' =>
                'The announcement must contain at least 20 characters.',

            'category.required' =>
                'Please select an announcement category.',

            'category.in' =>
                'Please select a valid barangay category.',

            'tags.required' =>
                'Please select at least one tag.',

            'tags.min' =>
                'Please select at least one tag.',

            'tags.*.exists' =>
                'One of the selected tags does not exist.',

            'tags.*.distinct' =>
                'Please do not select the same tag twice.',

            'is_published.boolean' =>
                'The publication option must be valid.',
        ];
    }
}
