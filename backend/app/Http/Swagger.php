<?php

namespace App\Http;

use OpenApi\Attributes as OA;

#[OA\Info(title: "API Dokumentasi Mini Project", version: "1.0.0")]
#[OA\Server(url: "/", description: "API Server Utama")]
class Swagger
{
    // File ini hanya untuk menampung anotasi Swagger global
}
