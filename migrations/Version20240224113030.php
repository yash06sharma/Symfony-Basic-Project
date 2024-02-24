<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224113030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE basic_detail (id INT AUTO_INCREMENT NOT NULL, database_user_id_id INT NOT NULL, state VARCHAR(255) NOT NULL, dist VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, INDEX IDX_D765945290F604E (database_user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE crud_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, age VARCHAR(255) NOT NULL, mobile VARCHAR(255) NOT NULL, cource VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE basic_detail ADD CONSTRAINT FK_D765945290F604E FOREIGN KEY (database_user_id_id) REFERENCES `database` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basic_detail DROP FOREIGN KEY FK_D765945290F604E');
        $this->addSql('DROP TABLE basic_detail');
        $this->addSql('DROP TABLE crud_entity');
    }
}
