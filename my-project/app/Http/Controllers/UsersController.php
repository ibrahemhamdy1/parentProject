<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = collect();

        // Request has provider query
        if($request->has('provider')) {
            $providerUsers = json_decode(Storage::get('providers/' . $request->get('provider') . '.json'), true);
            $users = $users->merge($this->getUsers($providerUsers));
        } else {
            foreach (Storage::files('providers') as $file) {
                $providerUsers = json_decode(Storage::get($file), true);
                $users = $users->merge($this->getUsers($providerUsers));
            }
        }

        return response($this->filterUsers($request, $users), 200);
    }

    /**
     * Filter users
     *
     * @param  \Illuminate\Support\Facades\Request  $request
     * @param  \Illuminate\Support\Collection  $users
     *
     * @return \Illuminate\Support\Collection
     */
    public function filterUsers($request, $users)
    {
        if ($request->has('perPage')) {
            $users = $users->paginate($request->get('perPage'));
        }

        if($request->has('statusCode')) {
            $users = $users->where('status', $request->get('statusCode'));
        }

        if($request->has('balanceMin') && $request->has('balanceMax')) {
            $users = $users->whereBetween('balance', [$request->get('balanceMin'), $request->get('balanceMax')]);
        }

        if($request->has('currency')) {
            $users = $users->where('currency', $request->get('currency'));
        }


        return $users;
    }

    /**
     * Get users array
     *
     * @param array  $providerUsers
     *
     * @return array
     */
    public function getUsers($providerUsers)
    {
        $users = [];

        foreach ($providerUsers as $providerUser) {
            $status = $providerUser['status'] ?? $providerUser['statusCode'];

            if($status == 1 || $status == 100) {
                $status = 'authorized';
            }

            if($status == 2 || $status == 200) {
                $status = 'decline';
            }

            if($status == 3 || $status == 300) {
                $status = 'refunded';
            }

            array_push($users,[
                'id' => $providerUser['id'] ?? $providerUser['parentIdentification'],
                'email' => $providerUser['email'] ??  $providerUser['parentEmail'],
                'status' => $status,
                'currency' => $providerUser['currency'] ?? $providerUser['Currency'],
                'balance' => $providerUser['parentAmount'] ?? $providerUser['balance'],
                'created_at' => $providerUser['created_at'] ?? $providerUser['registrationDate'],
            ]);
        }

        return $users;
    }
}
