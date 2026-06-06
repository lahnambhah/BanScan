<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class Index extends Component
{
    public $confirmingDelete = false;
    public $name;
    public $email;

    public $notify_low_stock;
    public $notify_sales;
    public $notify_new_items;
    public $notify_email;

    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $showPasswordForm = false;
    protected $listeners = ['proceedDeleteAccount' => 'deleteAccountConfirmed'];

    public function mount()
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->email = $user->email;

        $this->notify_low_stock = $user->notify_low_stock ?? false;
        $this->notify_sales = $user->notify_sales ?? false;
        $this->notify_new_items = $user->notify_new_items ?? false;
        $this->notify_email = $user->notify_email ?? false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:2',

            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/|unique:users,email,' . auth()->id(),
        ], [
            // NAME
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 2 characters.',

            // EMAIL
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.regex' => 'Only Gmail addresses are allowed (example@gmail.com).',
            'email.unique' => 'This email already exists.',
        ]);

        $user = auth()->user();

        $emailChanged = $user->email !== $this->email;

        $user->name = $this->name;

        if ($emailChanged) {

            $user->email = $this->email;

            // FORCE UNVERIFY
            $user->email_verified_at = null;

            $user->save();

            // SEND NEW VERIFICATION EMAIL
            $user->sendEmailVerificationNotification();

            $this->dispatch('emailChanged');

            return;
        }

        // NORMAL SAVE
        $user->update([
            'name' => $this->name,
            'notify_low_stock' => (bool) $this->notify_low_stock,
            'notify_sales' => (bool) $this->notify_sales,
            'notify_new_items' => (bool) $this->notify_new_items,
            'notify_email' => (bool) $this->notify_email,
        ]);

        $this->dispatch('saved');

        $user = auth()->user();

        // remove old notifications when turned OFF
        if (!$this->notify_sales) {
            $user->notifications()
                ->where('type', \App\Notifications\SaleNotification::class)
                ->delete();
        }

        if (!$this->notify_low_stock) {
            $user->notifications()
                ->where('type', \App\Notifications\LowStockNotification::class)
                ->delete();
        }

        if (!$this->notify_new_items) {
            $user->notifications()
                ->where('type', \App\Notifications\NewItemNotification::class)
                ->delete();
        }
    }

    public function deleteAccount()
    {
        $this->dispatch('confirmDeleteAccount');
    }

    public function deleteAccountConfirmed()
    {
        $user = auth()->user();
        Auth::logout();
        $user->delete();
        return redirect('/')->with('success', 'Account deleted');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required',
        ], [
            // CURRENT PASSWORD
            'current_password.required' => 'Current password is required.',
            // NEW PASSWORD
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'Password must be at least 6 characters.',
            'new_password.confirmed' => 'Passwords do not match.',

            // CONFIRM
            'new_password_confirmation.required' => 'Please confirm your password.',
        ]);

        if (!Hash::check($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        auth()->user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset([
            'current_password',
            'new_password',
            'new_password_confirmation'
        ]);

        $this->dispatch('saved');
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function updated($field)
    {
        if (str_starts_with($field, 'notify_')) {

            $user = auth()->user()->fresh();

            $user->update([
                $field => (bool) $this->$field
            ]);

            // also refresh component state
            $this->$field = $user->$field;
        }
    }
    public function render()
    {
        return view('livewire.settings.index');
    }
}