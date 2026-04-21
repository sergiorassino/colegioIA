<?php

namespace App\Livewire\Core\Legajos;

use App\Models\Core\Legajo;
use App\Models\Core\Nivel;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class LegajoForm extends Component
{
    public ?int $id = null;

    // ── Bloque 1: Personal ────────────────────────────────────
    public string  $apellido    = '';
    public string  $nombre      = '';
    public ?string $dni         = null;
    public string  $cuil        = '';
    public ?string $fechnaci    = null;
    public string  $sexo        = '';
    public string  $nacion      = '';
    public int     $idnivel     = 0;
    public string  $callenum    = '';
    public string  $barrio      = '';
    public string  $localidad   = '';
    public string  $codpos      = '';
    public string  $telefono    = '';
    public string  $email       = '';
    public string  $needes      = '';
    public string  $needes_detalle = '';
    public string  $vivecon     = '';
    public string  $ec_padres   = '';
    public string  $obs         = '';

    // ── Bloque 2: Madre ──────────────────────────────────────
    public string  $nombremad   = '';
    public string  $dnimad      = '';
    public string  $vivemad     = '';
    public ?string $fechnacmad  = null;
    public string  $nacionmad   = '';
    public string  $estacivimad = '';
    public string  $domimad     = '';
    public string  $cpmad       = '';
    public string  $ocupacmad   = '';
    public string  $sitlabmad   = '';
    public string  $lugtramad   = '';
    public string  $telemad     = '';
    public string  $telecelmad  = '';
    public string  $emailmad    = '';

    // ── Bloque 3: Padre ──────────────────────────────────────
    public string  $nombrepad   = '';
    public string  $dnipad      = '';
    public string  $vivepad     = '';
    public ?string $fechnacpad  = null;
    public string  $nacionpad   = '';
    public string  $estacivipad = '';
    public string  $domipad     = '';
    public string  $cppad       = '';
    public string  $ocupacpad   = '';
    public string  $sitlabpad   = '';
    public string  $lugtrapad   = '';
    public string  $telepad     = '';
    public string  $telecelpad  = '';
    public string  $emailpad    = '';

    // ── Bloque 4: Tutor ──────────────────────────────────────
    public string  $nombretut   = '';
    public ?int    $dnitut      = null;
    public string  $teletut     = '';
    public string  $emailtut    = '';
    public string  $ocupactut   = '';
    public string  $lugtratut   = '';

    // ── Bloque 5: Resp. Administrativo ───────────────────────
    public string  $respAdmiNom = '';
    public int     $respAdmiDni = 0;

    // ── Bloque 6: Reglamento ─────────────────────────────────
    public ?string $reglamApenom = null;
    public ?int    $reglamDni    = null;
    public ?string $reglamEmail  = null;

    // ── Otros ─────────────────────────────────────────────────
    public string  $emeravis    = '';
    public string  $retira      = '';
    public string  $retira1     = '';
    public string  $retira2     = '';
    public string  $contacto1   = '';
    public string  $contacto2   = '';
    public string  $contacto3   = '';

    public string $activeTab = 'personal';

    #[Computed]
    public function niveles()
    {
        return Nivel::orderBy('nivel')->get();
    }

    public function mount(?int $id = null): void
    {
        $this->id = $id;

        if ($this->id) {
            $legajo  = Legajo::findOrFail($this->id);
            $reflect = new \ReflectionClass($this);

            foreach ($legajo->toArray() as $key => $value) {
                if (! $reflect->hasProperty($key)) {
                    continue;
                }

                $prop = $reflect->getProperty($key);
                $type = $prop->getType();

                // Coerce null → '' only for non-nullable string properties
                if ($value === null && $type instanceof \ReflectionNamedType
                    && $type->getName() === 'string' && ! $type->allowsNull()) {
                    $value = '';
                }

                $this->{$key} = $value;
            }
        }
    }

    public function rules(): array
    {
        return [
            'apellido'  => ['required', 'string', 'max:50'],
            'nombre'    => ['required', 'string', 'max:50'],
            'dni'       => ['nullable', 'numeric', 'digits_between:7,10', 'unique:legajos,dni' . ($this->id ? ",{$this->id}" : '')],
            'idnivel'   => ['required', 'integer', 'exists:niveles,id'],
            'fechnaci'  => ['nullable', 'date'],
            'sexo'      => ['nullable', 'string', 'max:1'],
            'email'     => ['nullable', 'email', 'max:100'],
            'emailmad'  => ['nullable', 'email', 'max:50'],
            'emailpad'  => ['nullable', 'email', 'max:50'],
            'emailtut'  => ['nullable', 'email', 'max:50'],
            'reglamEmail'=> ['nullable', 'email', 'max:70'],
        ];
    }

    public function guardar(): void
    {
        $validated = $this->validate();

        $data = array_filter(array_merge($validated, [
            'apellido'    => $this->apellido,
            'nombre'      => $this->nombre,
            'dni'         => $this->dni,
            'cuil'        => $this->cuil,
            'fechnaci'    => $this->fechnaci,
            'sexo'        => $this->sexo,
            'nacion'      => $this->nacion,
            'idnivel'     => $this->idnivel,
            'callenum'    => $this->callenum,
            'barrio'      => $this->barrio,
            'localidad'   => $this->localidad,
            'codpos'      => $this->codpos,
            'telefono'    => $this->telefono,
            'email'       => $this->email,
            'needes'      => $this->needes,
            'needes_detalle' => $this->needes_detalle,
            'vivecon'     => $this->vivecon,
            'ec_padres'   => $this->ec_padres,
            'obs'         => $this->obs,
            // Madre
            'nombremad'   => $this->nombremad,
            'dnimad'      => $this->dnimad,
            'vivemad'     => $this->vivemad,
            'fechnacmad'  => $this->fechnacmad,
            'nacionmad'   => $this->nacionmad,
            'estacivimad' => $this->estacivimad,
            'domimad'     => $this->domimad,
            'cpmad'       => $this->cpmad,
            'ocupacmad'   => $this->ocupacmad,
            'sitlabmad'   => $this->sitlabmad,
            'lugtramad'   => $this->lugtramad,
            'telemad'     => $this->telemad,
            'telecelmad'  => $this->telecelmad,
            'emailmad'    => $this->emailmad,
            // Padre
            'nombrepad'   => $this->nombrepad,
            'dnipad'      => $this->dnipad,
            'vivepad'     => $this->vivepad,
            'fechnacpad'  => $this->fechnacpad,
            'nacionpad'   => $this->nacionpad,
            'estacivipad' => $this->estacivipad,
            'domipad'     => $this->domipad,
            'cppad'       => $this->cppad,
            'ocupacpad'   => $this->ocupacpad,
            'sitlabpad'   => $this->sitlabpad,
            'lugtrapad'   => $this->lugtrapad,
            'telepad'     => $this->telepad,
            'telecelpad'  => $this->telecelpad,
            'emailpad'    => $this->emailpad,
            // Tutor
            'nombretut'   => $this->nombretut,
            'dnitut'      => $this->dnitut,
            'teletut'     => $this->teletut,
            'emailtut'    => $this->emailtut,
            'ocupactut'   => $this->ocupactut,
            'lugtratut'   => $this->lugtratut,
            // Resp. Admi
            'respAdmiNom' => $this->respAdmiNom,
            'respAdmiDni' => $this->respAdmiDni,
            // Reglamento
            'reglamApenom'=> $this->reglamApenom,
            'reglamDni'   => $this->reglamDni,
            'reglamEmail' => $this->reglamEmail,
            // Otros
            'emeravis'    => $this->emeravis,
            'retira'      => $this->retira,
            'retira1'     => $this->retira1,
            'retira2'     => $this->retira2,
            'contacto1'   => $this->contacto1,
            'contacto2'   => $this->contacto2,
            'contacto3'   => $this->contacto3,
        ]), fn ($v) => $v !== null);

        if ($this->id) {
            Legajo::findOrFail($this->id)->update($data);
            session()->flash('success', 'Legajo actualizado correctamente.');
        } else {
            $legajo = Legajo::create($data);
            $this->id = $legajo->id;
            session()->flash('success', 'Legajo creado correctamente.');
            $this->redirect(route('staff.legajos.editar', $this->id), navigate: true);
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render(): View
    {
        return view('livewire.core.legajos.form')
            ->layout('layouts.staff');
    }
}
