<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260520000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change activite image column to TEXT for long image URLs';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE activite CHANGE image image LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE activite CHANGE image image VARCHAR(255) DEFAULT NULL');
    }
}
