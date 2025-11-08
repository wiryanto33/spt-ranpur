<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'nrp' => ['nullable', 'string', 'max:255', Rule::unique('users', 'nrp')->ignore($user->id)],
            'pangkat' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ])->validateWithBag('updateProfileInformation');

        // Handle profile image upload if present (store in public/uploads/users)
        if (request()->hasFile('image')) {
            // delete previous image (support old storage path and new uploads path)
            if ($user->image) {
                if (preg_match('/^uploads\//', $user->image)) {
                    @unlink(public_path($user->image));
                } else {
                    Storage::disk('public')->delete($user->image);
                }
            }
            $file = request()->file('image');
            $dir = public_path('uploads/users');
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $name = uniqid('user_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $name);
            $user->image = 'uploads/users/' . $name;
        }

        // If email verification is enforced and email changes
        if (!empty($input['email']) && $input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->fill([
                'name' => $input['name'],
                'email' => $input['email'] ?? $user->email,
                'nrp' => $input['nrp'] ?? $user->nrp,
                'pangkat' => $input['pangkat'] ?? $user->pangkat,
                'jabatan' => $input['jabatan'] ?? $user->jabatan,
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
