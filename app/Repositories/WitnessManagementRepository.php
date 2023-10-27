<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\InviteNotification;
use App\Notifications\WaliInvitationNotification;
use App\Notifications\WitnessInviteNotification;
use App\Repositories\Interfaces\WitnessInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class WitnessManagementRepository implements WitnessInterface
{
    public function getActivewitness($request = null){
        $witnesses = User::where('active_role','witness')->whereNotNull('created_by')->get();
        return $witnesses;
    }

    public function storeWitness($request){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
        ]);
        $password = generateStrongPassword();
        $form_data = $request->all();
        $form_data['active_role']  = 'witness';
        $form_data['is_accept'] = 1;
        $form_data['created_by'] = auth()->id();
        $form_data['password'] = Hash::make($password);
        $witness = User::create($form_data);

        $witness->notify(new WitnessInviteNotification($witness->refresh(), $password));
        return $witness;
    }
}
