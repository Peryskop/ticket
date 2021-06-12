<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210323104238 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_date (id INT AUTO_INCREMENT NOT NULL, movie_id INT NOT NULL, room_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_59F816778F93B6FC (movie_id), INDEX IDX_59F8167754177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, room_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_date ADD CONSTRAINT FK_59F816778F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_date ADD CONSTRAINT FK_59F8167754177093 FOREIGN KEY (room_id) REFERENCES room (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie_date DROP FOREIGN KEY FK_59F816778F93B6FC');
        $this->addSql('ALTER TABLE movie_date DROP FOREIGN KEY FK_59F8167754177093');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_date');
        $this->addSql('DROP TABLE room');
    }
}
