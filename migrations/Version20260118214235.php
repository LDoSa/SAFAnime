<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260118214235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opinion RENAME INDEX fk_ab02b027a76ed395 TO IDX_AB02B027A76ED395');
        $this->addSql('ALTER TABLE opinion RENAME INDEX fk_ab02b027794bbe89 TO IDX_AB02B027794BBE89');
        $this->addSql('DROP INDEX email ON user');
        $this->addSql('ALTER TABLE user CHANGE rol rol INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opinion RENAME INDEX idx_ab02b027a76ed395 TO FK_AB02B027A76ED395');
        $this->addSql('ALTER TABLE opinion RENAME INDEX idx_ab02b027794bbe89 TO FK_AB02B027794BBE89');
        $this->addSql('ALTER TABLE user CHANGE rol rol TINYINT DEFAULT 0 NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX email ON user (email)');
    }
}
