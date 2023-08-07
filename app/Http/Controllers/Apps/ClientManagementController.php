<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\ClientsDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientManagementController extends Controller
{
    public function index(ClientsDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.user-management.clients.list');
    }

    public function store(Request $request)
    {
//        $request->validate([
//
//        ]);
//
//        return Client::create($request->validated());
    }

    public function show(Client $client)
    {
//        return $client;
    }

    public function update(Request $request, Client $client)
    {
//        $request->validate([
//
//        ]);
//
//        $client->update($request->validated());
//
//        return $client;
    }

    public function destroy(Client $client)
    {
//        $client->delete();
//
//        return response()->json();
    }
}
