<?php

namespace App\Services;

use App\Models\Core\Calificacion;

/**
 * Adapta los campos multipropósito de la tabla `calificaciones` según el nivel educativo.
 *
 * La tabla calificaciones usa los campos ic01..ic40 / tm1..tm6 / dic / feb
 * con significados distintos según el nivel (Inicial, Primario, Secundario).
 *
 * Esta clase encapsula esa lógica y devuelve arrays con etiquetas legibles.
 */
class CalificacionesAdapter
{
    public const NIVEL_INICIAL    = 1;
    public const NIVEL_PRIMARIO   = 2;
    public const NIVEL_SECUNDARIO = 3;
    public const NIVEL_TERCIARIO  = 4;

    /**
     * Devuelve un array con las etiquetas y valores de los periodos de calificación
     * para el nivel dado.
     *
     * @return array<int, array{campo: string, etiqueta: string, valor: string|null}>
     */
    public function periodos(Calificacion $calificacion, int $nivelId): array
    {
        return match ($nivelId) {
            self::NIVEL_INICIAL    => $this->periodosInicial($calificacion),
            self::NIVEL_PRIMARIO   => $this->periodosPrimario($calificacion),
            self::NIVEL_SECUNDARIO,
            self::NIVEL_TERCIARIO  => $this->periodosSecundario($calificacion),
            default                => [],
        };
    }

    /**
     * Devuelve los trimestres de Inicial (ic01, ic02, ic03)
     */
    private function periodosInicial(Calificacion $c): array
    {
        return [
            ['campo' => 'ic01', 'etiqueta' => '1° Trimestre', 'valor' => $c->ic01],
            ['campo' => 'ic02', 'etiqueta' => '2° Trimestre', 'valor' => $c->ic02],
            ['campo' => 'ic03', 'etiqueta' => '3° Trimestre', 'valor' => $c->ic03],
        ];
    }

    /**
     * Devuelve los bimestres de Primario (ic01..ic04) + promedio anual
     */
    private function periodosPrimario(Calificacion $c): array
    {
        return [
            ['campo' => 'ic01', 'etiqueta' => '1° Bimestre',    'valor' => $c->ic01],
            ['campo' => 'ic02', 'etiqueta' => '2° Bimestre',    'valor' => $c->ic02],
            ['campo' => 'ic03', 'etiqueta' => '3° Bimestre',    'valor' => $c->ic03],
            ['campo' => 'ic04', 'etiqueta' => '4° Bimestre',    'valor' => $c->ic04],
            ['campo' => 'tm1',  'etiqueta' => '1° Semestre',    'valor' => $c->tm1],
            ['campo' => 'tm2',  'etiqueta' => '2° Semestre',    'valor' => $c->tm2],
            ['campo' => 'dic',  'etiqueta' => 'Diciembre',      'valor' => $c->dic],
            ['campo' => 'feb',  'etiqueta' => 'Febrero',        'valor' => $c->feb],
        ];
    }

    /**
     * Devuelve los trimestres de Secundario y Terciario (ic01..ic06) + nota trimestral + examen
     */
    private function periodosSecundario(Calificacion $c): array
    {
        return [
            ['campo' => 'ic01', 'etiqueta' => '1° Trimestre',    'valor' => $c->ic01],
            ['campo' => 'ic02', 'etiqueta' => '2° Trimestre',    'valor' => $c->ic02],
            ['campo' => 'ic03', 'etiqueta' => '3° Trimestre',    'valor' => $c->ic03],
            ['campo' => 'tm1',  'etiqueta' => 'Nota anual',      'valor' => $c->tm1],
            ['campo' => 'dic',  'etiqueta' => 'Diciembre',       'valor' => $c->dic],
            ['campo' => 'feb',  'etiqueta' => 'Febrero',         'valor' => $c->feb],
        ];
    }

    /**
     * Verifica si el alumno aprobó la materia considerando el nivel.
     */
    public function estaAprobada(Calificacion $c, int $nivelId): bool
    {
        if ($c->apro === 1) {
            return true;
        }

        // Para secundario: nota anual >= 6
        if (in_array($nivelId, [self::NIVEL_SECUNDARIO, self::NIVEL_TERCIARIO])) {
            $nota = (int) ($c->tm1 ?? 0);
            return $nota >= 6;
        }

        return false;
    }

    /**
     * Verifica si la materia está en condición de examen de diciembre o febrero.
     */
    public function tieneExamen(Calificacion $c): bool
    {
        return ($c->dic !== null && $c->dic !== '')
            || ($c->feb !== null && $c->feb !== '');
    }

    /**
     * Calcula el estado de la materia como string legible.
     */
    public function estado(Calificacion $c, int $nivelId): string
    {
        if ($this->estaAprobada($c, $nivelId)) {
            return 'Aprobada';
        }

        if ($c->feb !== null && $c->feb !== '') {
            return (int) $c->feb >= 4 ? 'Aprobada (Feb)' : 'Desaprobada';
        }

        if ($c->dic !== null && $c->dic !== '') {
            return 'Pendiente Feb';
        }

        return 'Cursando';
    }
}
