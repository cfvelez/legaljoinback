<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210620085808 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE storypoint ADD story_id INT NOT NULL');
        $this->addSql('ALTER TABLE storypoint ADD CONSTRAINT FK_7C2DEF11AA5D4036 FOREIGN KEY (story_id) REFERENCES story (id)');
        $this->addSql('CREATE INDEX IDX_7C2DEF11AA5D4036 ON storypoint (story_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE storypoint DROP FOREIGN KEY FK_7C2DEF11AA5D4036');
        $this->addSql('DROP INDEX IDX_7C2DEF11AA5D4036 ON storypoint');
        $this->addSql('ALTER TABLE storypoint DROP story_id');
    }
}
