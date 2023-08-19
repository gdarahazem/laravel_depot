<?php

namespace App\Http\Livewire\Product;

use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddProductModal extends Component

{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $avatar;
    public $saved_avatar;

    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required|string',
        'avatar' => 'nullable|sometimes|image|max:1024',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
    ];

    public function render()
    {

        return view('livewire.product.add-product-modal');
    }

    public function submit()
    {
//        dd(Auth::user()->id);
        // Validate the form input data
        $this->validate();

        DB::transaction(function () {
            // Prepare the data for creating a new Client
            $data = [
                'email' => $this->email,
                'fullname' => $this->name,
                'phonenumber' => $this->phone,
                'created_by' => Auth::user()->id,
            ];

            if ($this->avatar) {
                $data['profile_photo_path'] = $this->avatar->store('avatars', 'public');
            } else {
                $data['profile_photo_path'] = null;
            }

//            if (!$this->edit_mode) {
//                $data['password'] = Hash::make($this->email);
//            }

            // Create a new user record in the database
            $user = Client::updateOrCreate($data);

            if ($this->edit_mode) {
                // Assign selected role for user
//                $user->syncRoles($this->role);

                // Emit a success event with a message
                $this->emit('success', __('Client updated'));
            } else {
                // Assign selected role for user
//                $user->assignRole($this->role);

                // Send a password reset link to the user's email
//                Password::sendResetLink($user->only('email'));

                // Emit a success event with a message
                $this->emit('success', __('New user created'));
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Prevent deletion of current user
        if ($id == Auth::id()) {
            $this->emit('error', 'User cannot be deleted');
            return;
        }

        // Delete the user record with the specified ID
        Client::destroy($id);

        // Emit a success event with a message
        $this->emit('success', 'User successfully deleted');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $user = User::find($id);

        $this->saved_avatar = $user->profile_photo_url;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles?->first()->name ?? '';
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
