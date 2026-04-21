<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Configuración de Pest
|--------------------------------------------------------------------------
|
| Todos los tests de Feature usan RefreshDatabase para aislar la BD.
| Los tests de Unit son más rápidos y no necesitan la BD por defecto.
|
*/

uses(TestCase::class, RefreshDatabase::class)->in('Feature');
uses(TestCase::class)->in('Unit');
