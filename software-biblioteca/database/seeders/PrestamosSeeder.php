<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prestamo;
use Carbon\Carbon;

class PrestamosSeeder extends Seeder
{
    public function run()
    {
        // Crear 20 solicitudes (solo fecha_solicitud)
        for ($i = 1; $i <= 20; $i++) {
            $fechaSolicitud = Carbon::now()->subDays(rand(1, 365));
            while ($fechaSolicitud->isWeekend()) {
                $fechaSolicitud = $fechaSolicitud->subDay();
            }

            Prestamo::create([
                'id_usuario' => rand(1, 30), // IDs de usuarios entre 1 y 30
                'id_libro' => rand(1, 769), // IDs de libros entre 1 y 769
                'fecha_solicitud' => $fechaSolicitud->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Crear 80 préstamos activos (fecha_solicitud y fecha_prestamo)
        for ($i = 1; $i <= 80; $i++) {
            $fechaSolicitud = Carbon::now()->subDays(rand(1, 365));
            while ($fechaSolicitud->isWeekend()) {
                $fechaSolicitud = $fechaSolicitud->subDay();
            }

            $fechaPrestamo = (clone $fechaSolicitud)->addDays(rand(1, 2));
            while ($fechaPrestamo->isWeekend()) {
                $fechaPrestamo = $fechaPrestamo->addDay();
            }

            Prestamo::create([
                'id_usuario' => rand(1, 30), // IDs de usuarios entre 1 y 30
                'id_libro' => rand(1, 769), // IDs de libros entre 1 y 769
                'fecha_solicitud' => $fechaSolicitud->format('Y-m-d'),
                'fecha_prestamo' => $fechaPrestamo->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Crear 100 préstamos completados (fecha_solicitud, fecha_prestamo, fecha_devolucion)
        for ($i = 1; $i <= 100; $i++) {
            $fechaSolicitud = Carbon::now()->subDays(rand(1, 365));
            while ($fechaSolicitud->isWeekend()) {
                $fechaSolicitud = $fechaSolicitud->subDay();
            }

            $fechaPrestamo = (clone $fechaSolicitud)->addDays(rand(1, 2));
            while ($fechaPrestamo->isWeekend()) {
                $fechaPrestamo = $fechaPrestamo->addDay();
            }

            $fechaDevolucion = (clone $fechaPrestamo)->addDays(rand(7, 15));
            while ($fechaDevolucion->isWeekend()) {
                $fechaDevolucion = $fechaDevolucion->addDay();
            }

            // Aseguramos que haya más préstamos devueltos correctamente
            $devuelto = rand(1, 10) <= 8 ? 'Si' : 'No'; // 80% de los préstamos serán devueltos correctamente

            Prestamo::create([
                'id_usuario' => rand(1, 30), // IDs de usuarios entre 1 y 30
                'id_libro' => rand(1, 769), // IDs de libros entre 1 y 769
                'fecha_solicitud' => $fechaSolicitud->format('Y-m-d'),
                'fecha_prestamo' => $fechaPrestamo->format('Y-m-d'),
                'fecha_devolucion' => $fechaDevolucion->format('Y-m-d'),
                'devuelto' => $devuelto,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
