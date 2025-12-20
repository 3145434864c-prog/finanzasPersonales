<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Movimiento;
use App\Models\Presupuesto;
use App\Models\Ahorro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Cesar David',
            'password' => Hash::make('admin123'),
        ]);

        // Crear categorías
        $categorias = [
            ['nombre' => 'Salario', 'tipo' => 'ingreso'],
            ['nombre' => 'Freelance', 'tipo' => 'ingreso'],
            ['nombre' => 'Inversiones', 'tipo' => 'ingreso'],
            ['nombre' => 'Alimentación', 'tipo' => 'gasto'],
            ['nombre' => 'Transporte', 'tipo' => 'gasto'],
            ['nombre' => 'Entretenimiento', 'tipo' => 'gasto'],
            ['nombre' => 'Servicios', 'tipo' => 'gasto'],
            ['nombre' => 'Salud', 'tipo' => 'gasto'],
            ['nombre' => 'Educación', 'tipo' => 'gasto'],
            ['nombre' => 'Ropa', 'tipo' => 'gasto'],
            ['nombre' => 'Viajes', 'tipo' => 'gasto'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['nombre' => $categoria['nombre'], 'tipo' => $categoria['tipo']],
                $categoria
            );
        }

        // Obtener categorías creadas
        $categoriaSalario = Categoria::where('nombre', 'Salario')->first();
        $categoriaFreelance = Categoria::where('nombre', 'Freelance')->first();
        $categoriaInversiones = Categoria::where('nombre', 'Inversiones')->first();
        $categoriaAlimentacion = Categoria::where('nombre', 'Alimentación')->first();
        $categoriaTransporte = Categoria::where('nombre', 'Transporte')->first();
        $categoriaEntretenimiento = Categoria::where('nombre', 'Entretenimiento')->first();
        $categoriaServicios = Categoria::where('nombre', 'Servicios')->first();
        $categoriaSalud = Categoria::where('nombre', 'Salud')->first();
        $categoriaRopa = Categoria::where('nombre', 'Ropa')->first();
        $categoriaViajes = Categoria::where('nombre', 'Viajes')->first();
        $categoriaEducacion = Categoria::where('nombre', 'Educación')->first();

        // Crear movimientos (últimos 24 meses)
        $movimientos = [];
        for ($yearOffset = 0; $yearOffset <= 1; $yearOffset++) {
            $currentYear = Carbon::now()->year - $yearOffset;
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create($currentYear, $month, rand(1, 28));
                // Salario mensual
                $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaSalario->id, 'tipo' => 'ingreso', 'monto' => 2500000.00, 'descripcion' => 'Salario mensual ' . $date->format('F Y'), 'fecha' => $date];
                // Alimentación
                $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaAlimentacion->id, 'tipo' => 'gasto', 'monto' => rand(380000, 480000), 'descripcion' => 'Supermercado ' . $date->format('F Y'), 'fecha' => $date];
                // Transporte
                $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaTransporte->id, 'tipo' => 'gasto', 'monto' => rand(110000, 140000), 'descripcion' => 'Gasolina ' . $date->format('F Y'), 'fecha' => $date];
                // Servicios (cada 2 meses)
                if ($month % 2 == 0) {
                    $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaServicios->id, 'tipo' => 'gasto', 'monto' => rand(135000, 160000), 'descripcion' => 'Electricidad ' . $date->format('F Y'), 'fecha' => $date];
                    $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaServicios->id, 'tipo' => 'gasto', 'monto' => rand(80000, 140000), 'descripcion' => 'Agua ' . $date->format('F Y'), 'fecha' => $date];
                    $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaServicios->id, 'tipo' => 'gasto', 'monto' => 80000.00, 'descripcion' => 'Internet ' . $date->format('F Y'), 'fecha' => $date];
                }
            }
            // Ingresos adicionales
            $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaFreelance->id, 'tipo' => 'ingreso', 'monto' => 800000.00, 'descripcion' => 'Proyecto freelance web', 'fecha' => Carbon::create($currentYear, rand(1,12), rand(1,28))];
            $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaFreelance->id, 'tipo' => 'ingreso', 'monto' => 1200000.00, 'descripcion' => 'Desarrollo app móvil', 'fecha' => Carbon::create($currentYear, rand(1,12), rand(1,28))];
            $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaInversiones->id, 'tipo' => 'ingreso', 'monto' => 150000.00, 'descripcion' => 'Dividendos acciones', 'fecha' => Carbon::create($currentYear, rand(1,12), rand(1,28))];
            // Gastos adicionales
            $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaEntretenimiento->id, 'tipo' => 'gasto', 'monto' => rand(60000, 120000), 'descripcion' => 'Entretenimiento ' . $currentYear, 'fecha' => Carbon::create($currentYear, rand(1,12), rand(1,28))];
            $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaSalud->id, 'tipo' => 'gasto', 'monto' => rand(50000, 200000), 'descripcion' => 'Salud ' . $currentYear, 'fecha' => Carbon::create($currentYear, rand(1,12), rand(1,28))];
            $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaEducacion->id, 'tipo' => 'gasto', 'monto' => rand(250000, 300000), 'descripcion' => 'Educación ' . $currentYear, 'fecha' => Carbon::create($currentYear, rand(1,12), rand(1,28))];
            $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaRopa->id, 'tipo' => 'gasto', 'monto' => rand(120000, 180000), 'descripcion' => 'Ropa ' . $currentYear, 'fecha' => Carbon::create($currentYear, rand(1,12), rand(1,28))];
            $movimientos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaViajes->id, 'tipo' => 'gasto', 'monto' => rand(600000, 800000), 'descripcion' => 'Viaje ' . $currentYear, 'fecha' => Carbon::create($currentYear, rand(1,12), rand(1,28))];
        }

        foreach ($movimientos as $movimiento) {
            Movimiento::create($movimiento);
        }

        // Crear presupuestos (últimos 24 meses)
        $presupuestos = [];
        for ($yearOffset = 0; $yearOffset <= 1; $yearOffset++) {
            $currentYear = Carbon::now()->year - $yearOffset;
            for ($month = 1; $month <= 12; $month++) {
                $presupuestos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaAlimentacion->id, 'monto_asignado' => 500000.00, 'mes' => $month, 'anio' => $currentYear];
                $presupuestos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaTransporte->id, 'monto_asignado' => 150000.00, 'mes' => $month, 'anio' => $currentYear];
                $presupuestos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaEntretenimiento->id, 'monto_asignado' => 200000.00, 'mes' => $month, 'anio' => $currentYear];
                $presupuestos[] = ['user_id' => $user->id, 'categoria_id' => $categoriaServicios->id, 'monto_asignado' => 400000.00, 'mes' => $month, 'anio' => $currentYear];
            }
        }

        foreach ($presupuestos as $presupuesto) {
            Presupuesto::create($presupuesto);
        }

        // Crear ahorros
        $ahorros = [
            [
                'tipo_ahorro' => 'meta',
                'descripcion' => 'Fondo de emergencia',
                'monto_ahorrado' => 1200000.00,
                'nombre_meta' => 'Fondo de emergencia',
                'monto_objetivo' => 5000000.00,
                'periodicidad' => 'mensual',
                'monto_aporte' => 200000.00,
                'fecha_inicio' => Carbon::now()->subMonths(6),
                'fecha_objetivo' => Carbon::now()->addMonths(12),
                'estado' => 'activo',
            ],
            [
                'tipo_ahorro' => 'meta',
                'descripcion' => 'Vacaciones 2025',
                'monto_ahorrado' => 800000.00,
                'nombre_meta' => 'Vacaciones Europa',
                'monto_objetivo' => 3000000.00,
                'periodicidad' => 'mensual',
                'monto_aporte' => 150000.00,
                'fecha_inicio' => Carbon::now()->subMonths(4),
                'fecha_objetivo' => Carbon::now()->addMonths(16),
                'estado' => 'activo',
            ],
            [
                'tipo_ahorro' => 'meta',
                'descripcion' => 'Compra de auto',
                'monto_ahorrado' => 0.00,
                'nombre_meta' => 'Compra de auto nuevo',
                'monto_objetivo' => 15000000.00,
                'periodicidad' => 'mensual',
                'monto_aporte' => 300000.00,
                'fecha_inicio' => Carbon::now()->subMonths(2),
                'fecha_objetivo' => Carbon::now()->addMonths(48),
                'estado' => 'activo',
            ],
        ];

        foreach ($ahorros as $ahorro) {
            Ahorro::create($ahorro);
        }
    }
}
