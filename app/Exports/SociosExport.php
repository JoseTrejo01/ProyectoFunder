<?php

namespace App\Exports;

use App\Models\Socio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SociosExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Socio::query()->where('estado', 1);

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('Nombre_Beneficiario', 'like', "%$search%")
                  ->orWhere('DNI', 'like', "%$search%")
                  ->orWhere('Telefono', 'like', "%$search%");
            });
        }
        if (!empty($this->filters['genero'])) {
            $query->where('genero', $this->filters['genero']);
        }
        if (!empty($this->filters['localidad'])) {
            $query->where('direccion', 'like', "%{$this->filters['localidad']}%");
        }
        if (!empty($this->filters['tipo'])) {
            $query->where('Tipo_De_Socio', 'like', "%{$this->filters['tipo']}%");
        }

                if (!empty($this->filters['departamento'])) {
            $query->where('departamento', $this->filters['departamento']);
        }
        if (!empty($this->filters['estado_civil'])) {
            $query->where('estado_civil', $this->filters['estado_civil']);
        }
        if (!empty($this->filters['nivel_educativo'])) {
            $query->where('nivel_educativo', $this->filters['nivel_educativo']);
        }
        if (!empty($this->filters['edad'])) {
            $query->where('edad', $this->filters['edad']);
        }

        return $query->get();
    }

        public function headings(): array
    {
        return [
            'ID',
            'Id_Organizacion',
            'Nombre',
            'DNI',
            'Tipo Cargo',
            'Tipo Socio',
            'Teléfono',
            'Género',
            'Fecha Nacimiento',
            'Edad',
            'Estado Civil',
            'Nivel Educativo',
            'Medio de Comunicación',
            'Departamento',
            'Municipio',
            'Comunidad',
            'Dirección',
            'Actividad Económica',
            'Actividad No Agrícola',
            'Categoría',
            'Estado',
            'Creado',
            'Actualizado'
        ];
    }

}
