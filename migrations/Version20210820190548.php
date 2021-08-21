<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210820190548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE daily_flow (id INT AUTO_INCREMENT NOT NULL, source_id INT NOT NULL, date DATETIME NOT NULL, medium_flowrate DOUBLE PRECISION NOT NULL, maximum_flowrate DOUBLE PRECISION NOT NULL, minimum_flowrate DOUBLE PRECISION NOT NULL, INDEX IDX_16C6C9E5953C1C61 (source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hourly_flow (id INT AUTO_INCREMENT NOT NULL, source_id INT NOT NULL, date DATETIME NOT NULL, medium_flowrate DOUBLE PRECISION NOT NULL, maximum_flowrate DOUBLE PRECISION NOT NULL, minimum_flowrate DOUBLE PRECISION NOT NULL, INDEX IDX_5CC1A2F5953C1C61 (source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sensors (id INT AUTO_INCREMENT NOT NULL, dev_eui VARCHAR(255) NOT NULL, last_seen DATETIME DEFAULT NULL, last_battery DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sensors_uplinks (id INT AUTO_INCREMENT NOT NULL, sensor_id INT NOT NULL, date DATETIME NOT NULL, battery DOUBLE PRECISION DEFAULT NULL, water_flow_rate DOUBLE PRECISION DEFAULT NULL, INDEX IDX_B9275AF5A247991F (sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sources (id INT AUTO_INCREMENT NOT NULL, sensor_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D25D65F2A247991F (sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE daily_flow ADD CONSTRAINT FK_16C6C9E5953C1C61 FOREIGN KEY (source_id) REFERENCES sources (id)');
        $this->addSql('ALTER TABLE hourly_flow ADD CONSTRAINT FK_5CC1A2F5953C1C61 FOREIGN KEY (source_id) REFERENCES sources (id)');
        $this->addSql('ALTER TABLE sensors_uplinks ADD CONSTRAINT FK_B9275AF5A247991F FOREIGN KEY (sensor_id) REFERENCES sensors (id)');
        $this->addSql('ALTER TABLE sources ADD CONSTRAINT FK_D25D65F2A247991F FOREIGN KEY (sensor_id) REFERENCES sensors (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sensors_uplinks DROP FOREIGN KEY FK_B9275AF5A247991F');
        $this->addSql('ALTER TABLE sources DROP FOREIGN KEY FK_D25D65F2A247991F');
        $this->addSql('ALTER TABLE daily_flow DROP FOREIGN KEY FK_16C6C9E5953C1C61');
        $this->addSql('ALTER TABLE hourly_flow DROP FOREIGN KEY FK_5CC1A2F5953C1C61');
        $this->addSql('DROP TABLE daily_flow');
        $this->addSql('DROP TABLE hourly_flow');
        $this->addSql('DROP TABLE sensors');
        $this->addSql('DROP TABLE sensors_uplinks');
        $this->addSql('DROP TABLE sources');
    }
}
