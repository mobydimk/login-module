<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LoginModule\Platform\PlatformRequest;
use App\OAuthClient\Manager;
use App\Http\Requests\Admin\StoreClientRequest;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.clients.index', [
            'clients' => Client::get()
        ]);       //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client = new Client([
            'secret' => str_random(40)
        ]);
        return view('admin.clients.form', [
            'client' => $client,
            'profile_fields' => $this->getProfileFields(),
            'providers' => Manager::providers()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        $client = new Client($request->all());
        $client->personal_access_client = false;
        $client->password_client = false;
        $client->save();
        return redirect()
            ->route('admin.clients.index')
            ->with('status', 'New client added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('admin.clients.form', [
            'client' => $client,
            'profile_fields' => $this->getProfileFields(),
            'providers' => Manager::providers()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(StoreClientRequest $request, Client $client)
    {
        $client->fill($request->all());
        $client->save();
        return redirect()
            ->route('admin.clients.index')
            ->with('status', 'Client updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()
            ->route('admin.clients.index')
            ->with('status', 'Client deleted.');
    }



    private function getProfileFields() {
        $pf = PlatformRequest::profileFields();
        return array_merge($pf->getAll(), $pf::VERIFICATION_FIELDS);
    }
}
