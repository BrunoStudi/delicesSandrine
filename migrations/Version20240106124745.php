<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240106124745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diet ADD diet_name VARCHAR(255) NOT NULL, DROP hyper_prot, DROP veget, DROP without_salt, DROP detox');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diet ADD veget VARCHAR(255) NOT NULL, ADD without_salt VARCHAR(255) NOT NULL, ADD detox VARCHAR(255) NOT NULL, CHANGE diet_name hyper_prot VARCHAR(255) NOT NULL');
    }
}
