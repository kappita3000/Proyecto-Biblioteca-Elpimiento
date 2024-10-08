<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class prestamo extends Model
{
    
    protected $primaryKey = 'ID'; // Si la clave primaria es `ID`
    protected $table = 'prestamo'; // Nombre de la tabla

    protected $fillable = ['ID_Usuario', 'ID_Libro', 'fecha_recoLibro', 'Devuelto'];
    public $timestamps = false; // Desactivar timestamps automáticas

}
