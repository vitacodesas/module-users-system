<?php

namespace Vitacode\Database\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportDatabaseCommand extends Command
{
    protected $signature = 'db:export 
                                {--path=database/exports : Ruta donde guardar los archivos} 
                                {--conection=mysql : Conexión de la base de datos}
                                {--limitData=1000 : Cantidad de datos a exportar por tabla}';

    protected $description = 'Exporta la base de datos en archivos separados para cada tabla (estructura y datos)';

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
            //code...
            $outputPath = $this->option('path');
            $connName = $this->option('conection');



            // Imprimir al final de la ejecución el tiempo que tomó la exportación
            $startTime = microtime(true);
            register_shutdown_function(function () use ($startTime) {
                $endTime = microtime(true);
                $elapsed = $endTime - $startTime;
                $this->info("Tiempo de ejecución: $elapsed segundos");
            });
            $this->info("Exportando base de datos...");

            // Aliminando archivos anteriores
            Storage::deleteDirectory($outputPath);


            Storage::makeDirectory($outputPath);

            $database = config("database.connections.{$connName}.database");


            $this->db = DB::connection($connName);

            $tables = $this->db->select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'", [$database]);


            // exportar stored procedures
            $procedures = $this->db->select("SHOW PROCEDURE STATUS WHERE Db = ?", [$database]);
            foreach ($procedures as $procedure) {
                try {
                    $procedureName = $procedure->Name;
                    $procedureDef = $this->db->selectOne("SHOW CREATE PROCEDURE $procedureName");

                    if (!isset($procedureDef->{'Create Procedure'})) {
                        $this->warn("No se pudo obtener la definición del procedimiento almacenado {$procedure->Name}");
                        continue;
                    }
                    $procedureDef = $procedureDef->{'Create Procedure'};
                    // quitar definer
                    $this->deleteDefiner($procedureDef);
                    $filePath = "$outputPath/procedures/$procedureName.sql";
                    Storage::put($filePath, $procedureDef . ";\n");
                } catch (\Throwable $th) {
                    $this->warn("Error al exportar el procedimiento almacenado {$procedure->Name}: {$th->getMessage()}");
                }
            }

            // exportar funciones
            $functions = $this->db->select("SHOW FUNCTION STATUS WHERE Db = ?", [$database]);
            foreach ($functions as $function) {
                $functionName = $function->Name;
                $functionDef = $this->db->selectOne("SHOW CREATE FUNCTION $functionName");
                if (!isset($functionDef->{'Create Function'})) {
                    $this->warn("No se pudo obtener la definición de la función {$function->Name}");
                    continue;
                }
                $functionDef = $functionDef->{'Create Function'};
                $this->deleteDefiner($functionDef);
                $filePath = "$outputPath/functions/$functionName.sql";
                Storage::put($filePath, $functionDef . ";\n");
            }


            foreach ($tables as $table) {
                $tableName = $table->TABLE_NAME;

                if ($tableName == 'export_matriz_emolumentos' || true) {
                    // continue;
                    $this->info("Exportando: $tableName");

                    // Exportar estructura
                    $this->exportTableStructure($tableName, $outputPath);

                    // Exportar datos en lotes
                    $this->exportTableData($tableName, $outputPath);
                    // break;


                }
            }
        } catch (\Throwable $th) {
            $this->error("Error: {$th->getMessage()} en la línea {$th->getLine()} del archivo {$th->getFile()}");
        }
        $this->info("Exportación completada. Archivos guardados en $outputPath.");
    }


    protected function exportTableStructure($tableName, $outputPath)
    {
        $structure = $this->db->select("SHOW CREATE TABLE `$tableName`")[0]->{'Create Table'};
        $filePath = "$outputPath/tables/$tableName-structure.sql";
        Storage::put($filePath, $structure . ";\n");
    }

    protected function getPrimaryKey($tableName)
    {
        $indexInfo = $this->db->select("SHOW KEYS FROM `$tableName` WHERE Key_name = 'PRIMARY'");
        return $indexInfo[0]->Column_name ?? null; // Suponer 'id' si no hay clave primaria
    }

    protected function getFirstColumn($tableName)
    {
        // Obtener la primera columna de la tabla usando SHOW COLUMNS
        $columns = $this->db->select("SHOW COLUMNS FROM `$tableName`");
        return $columns[0]->Field ?? null;
    }


    protected function exportTableData($tableName, $outputPath)
    {
        $filePath = "$outputPath/data/data-$tableName.sql";
        $limitData = $this->option('limitData');

        // Determinar la columna para ordenar
        $orderColumn = $this->getPrimaryKey($tableName) ?? $this->getFirstColumn($tableName);
        
        $rows = $this->db->table($tableName)
        ->orderBy($orderColumn)
        ->limit($limitData)->get();
        
        $inserts = [];
        foreach ($rows as $row) {
            $values = array_map(fn($value) => is_null($value) ? 'NULL' : DB::getPdo()->quote($value), (array)$row);
            $inserts[] = '(' . implode(', ', $values) . ')';
        }
        
        if (empty($inserts)) {
            return;
        }
        $sql = "INSERT INTO `$tableName` VALUES " . implode(",\n", $inserts) . ";\n";
        Storage::append($filePath, $sql);
    }


    protected function deleteDefiner(&$sql)
    {

        // Eliminar la cláusula DEFINER de la definición del procedimiento
        $sql = preg_replace('/DEFINER=`[^`]+`@`[^`]+`/', '', $sql);
        
    }

    

    
}
