<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228102534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD tempsprepa INT NOT NULL, ADD tempscuisson INT NOT NULL, ADD tempsrepos INT DEFAULT NULL, DROP temps');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `article` ADD temps LONGTEXT NOT NULL, DROP tempsprepa, DROP tempscuisson, DROP tempsrepos');
    }
}
