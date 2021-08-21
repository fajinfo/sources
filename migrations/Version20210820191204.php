<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210820191204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sources_admin (sources_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_BCD497E4DD51D0F7 (sources_id), INDEX IDX_BCD497E4A76ED395 (user_id), PRIMARY KEY(sources_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sources_view (sources_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4ABEC5E7DD51D0F7 (sources_id), INDEX IDX_4ABEC5E7A76ED395 (user_id), PRIMARY KEY(sources_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sources_admin ADD CONSTRAINT FK_BCD497E4DD51D0F7 FOREIGN KEY (sources_id) REFERENCES sources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sources_admin ADD CONSTRAINT FK_BCD497E4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sources_view ADD CONSTRAINT FK_4ABEC5E7DD51D0F7 FOREIGN KEY (sources_id) REFERENCES sources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sources_view ADD CONSTRAINT FK_4ABEC5E7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sources_admin DROP FOREIGN KEY FK_BCD497E4A76ED395');
        $this->addSql('ALTER TABLE sources_view DROP FOREIGN KEY FK_4ABEC5E7A76ED395');
        $this->addSql('DROP TABLE sources_admin');
        $this->addSql('DROP TABLE sources_view');
        $this->addSql('DROP TABLE user');
    }
}
