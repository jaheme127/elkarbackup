<?php
namespace App\Migrations;

trait LegacyBackupDirAwareTrait
{
    private string $backupDir;

    public function setLegacyBackupDir($backupDir): void
    {
        $this->backupDir = $backupDir;
    }
}

