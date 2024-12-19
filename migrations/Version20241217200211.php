<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217200211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
        // $this->addSql('CREATE UNIQUE INDEX UNIQ_964685A6E5B533F9 ON consultation (appointment_id)');
        $this->addSql('ALTER TABLE doctor ADD specialty VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6E5B533F9');
        // $this->addSql('DROP INDEX UNIQ_964685A6E5B533F9 ON consultation');
        $this->addSql('ALTER TABLE doctor DROP specialty');
    }
}
