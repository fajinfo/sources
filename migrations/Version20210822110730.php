<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210822110730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sensors ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sensors ADD CONSTRAINT FK_D0D3FA90953C1C61 FOREIGN KEY (source_id) REFERENCES sources (id)');
        $this->addSql('CREATE INDEX IDX_D0D3FA90953C1C61 ON sensors (source_id)');
        $this->addSql('ALTER TABLE sources DROP FOREIGN KEY FK_D25D65F2A247991F');
        $this->addSql('DROP INDEX IDX_D25D65F2A247991F ON sources');
        $this->addSql('ALTER TABLE sources DROP sensor_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sensors DROP FOREIGN KEY FK_D0D3FA90953C1C61');
        $this->addSql('DROP INDEX IDX_D0D3FA90953C1C61 ON sensors');
        $this->addSql('ALTER TABLE sensors DROP source_id');
        $this->addSql('ALTER TABLE sources ADD sensor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sources ADD CONSTRAINT FK_D25D65F2A247991F FOREIGN KEY (sensor_id) REFERENCES sensors (id)');
        $this->addSql('CREATE INDEX IDX_D25D65F2A247991F ON sources (sensor_id)');
    }
}
