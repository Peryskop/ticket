<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210501140003 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, movie_date_id INT NOT NULL, slot_id INT NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, INDEX IDX_97A0ADA398017445 (movie_date_id), UNIQUE INDEX UNIQ_97A0ADA359E5119C (slot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA398017445 FOREIGN KEY (movie_date_id) REFERENCES movie_date (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA359E5119C FOREIGN KEY (slot_id) REFERENCES slot (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ticket');
    }
}
