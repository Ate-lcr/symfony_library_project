<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713151948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD written_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331AB69C8EF FOREIGN KEY (written_by_id) REFERENCES author (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331AB69C8EF ON book (written_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331AB69C8EF');
        $this->addSql('DROP INDEX IDX_CBE5A331AB69C8EF ON book');
        $this->addSql('ALTER TABLE book DROP written_by_id');
    }
}
