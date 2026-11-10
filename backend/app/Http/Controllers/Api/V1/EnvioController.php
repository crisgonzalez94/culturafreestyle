<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnvioRequest;
use App\Http\Resources\EnvioResource;
use App\Models\Envio;
use App\Notifications\EnvioRecibido;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;

class EnvioController extends Controller
{
    /**
     * Guarda un evento enviado desde "Sumá tu evento" y avisa por mail.
     */
    public function store(StoreEnvioRequest $request): JsonResponse
    {
        $envio = Envio::create($request->safe()->except('web'));

        if ($avisoEmail = config('cultura.aviso_email')) {
            Notification::route('mail', $avisoEmail)->notify(new EnvioRecibido($envio));
        }

        return EnvioResource::make($envio)->response()->setStatusCode(201);
    }
}
