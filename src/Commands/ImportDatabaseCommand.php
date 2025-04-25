<?php

namespace Vitacode\Database\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportDatabaseCommand extends Command
{
    protected $signature = 'db:import {--path=database/exports : Ruta de los archivos de exportación}
                                     {--conection= : Conexión de la base de datos}';
    protected $description = 'Importa las tablas y datos desde archivos SQL separados';
    private $db;
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $inputPath = $this->option('path');
            $connName = $this->option('conection');

            if (!isset($connName) || empty($connName)) {
                $this->error("EL parámetro --conection es obligatorio.");
                return false;
            }


            // Verificar si el directorio existe
            if (!Storage::exists($inputPath)) {
                $this->error("El directorio $inputPath no existe.");
                return true;
            }

            $this->info("Importando base de datos desde $inputPath...");

            $this->db = DB::connection($connName);

            // Desactivar temporalmente las claves foráneas
            $this->db->statement('SET FOREIGN_KEY_CHECKS = 0;');

            // Importar la estructura de las tablas
            $this->importTableStructures("$inputPath/tables");

            // Importar los datos de las tablas
            $this->importTableData("$inputPath/data");

            // Importar los procedimientos almacenados
            $this->importStoredProcedures("$inputPath/procedures");

            // Importar las funciones almacenadas
            $this->importFunctions("$inputPath/functions");

            // Reactivar las claves foráneas
            $this->db->statement('SET FOREIGN_KEY_CHECKS = 1;');

            $this->info("Importación completada.");
            return true;
        } catch (\Throwable $th) {
            $this->error("Error: {$th->getMessage()} en la línea {$th->getLine()} del archivo {$th->getFile()}");
            return false;
        }
    }

    protected function existTable($tableName)
    {
        try {
            $this->db->table($tableName)->first();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Importa la estructura de las tablas.
     */
    protected function importTableStructures($path)
    {
        if (!Storage::exists($path)) {
            $this->warn("No se encontró el directorio $path para las estructuras.");
            return;
        }

        $files = Storage::files($path);

        foreach ($files as $file) {
            $this->info("Importando estructura desde $file...");
            $sql = Storage::get($file);

            // Eliminar la tabla si ya existe el nombre viene así database/exports/tables/account_homologation_tl-structure.sql y el nombre es este account_homologation_tl
            $tableName = pathinfo($file, PATHINFO_FILENAME);
            $tableName = str_replace('-structure', '', $tableName);
            //si la tabla existe retornar mensaje de error
            if ($this->existTable($tableName)) {
                throw new \Exception("La tabla $tableName ya existe, recomendamos limpiar toda el schema antes de importar.");
            }
            $this->db->unprepared("DROP TABLE IF EXISTS `$tableName`");
            $this->db->unprepared($sql);
        }
    }

    /**
     * Importa los datos de las tablas.
     */
    protected function importTableData($path)
    {
        if (!Storage::exists($path)) {
            $this->warn("No se encontró el directorio $path para los datos.");
            return;
        }

        $files = Storage::files($path);

        foreach ($files as $file) {
            $this->info("Importando datos desde $file...");
            $sql = Storage::get($file);
            $this->db->unprepared($sql);
        }
    }


    protected function importStoredProcedures($path)
    {
        if (!Storage::exists($path)) {
            $this->warn("No se encontró el directorio $path para los procedimientos almacenados.");
            return;
        }

        $files = Storage::files($path);

        foreach ($files as $file) {
            $this->info("Importando procedimiento almacenado desde $file...");
            $sql = Storage::get($file);
            try {
                $this->db->unprepared($sql);
            } catch (\Throwable $th) {
                $this->warn("Error al importar el procedimiento almacenado desde $file: {$th->getMessage()}");
                continue;
            }
        }
    }

    protected function importFunctions($path){
        if (!Storage::exists($path)) {
            $this->warn("No se encontró el directorio $path para las funciones almacenadas.");
            return;
        }

        $files = Storage::files($path);

        foreach ($files as $file) {
            $this->info("Importando función almacenada desde $file...");
            $sql = Storage::get($file);
            try {
                $this->db->unprepared($sql);
            } catch (\Throwable $th) {
                $this->warn("Error al importar la función almacenada desde $file: {$th->getMessage()}");
                continue;
            }
        }
    }
}
