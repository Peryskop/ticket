<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210502155915 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room ADD cinema_id INT NOT NULL, ADD status INT NOT NULL');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519BB4CB84B6 FOREIGN KEY (cinema_id) REFERENCES cinema (id)');
        $this->addSql('CREATE INDEX IDX_729F519BB4CB84B6 ON room (cinema_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519BB4CB84B6');
        $this->addSql('DROP INDEX IDX_729F519BB4CB84B6 ON room');
        $this->addSql('ALTER TABLE room DROP cinema_id, DROP status');
    }
}
