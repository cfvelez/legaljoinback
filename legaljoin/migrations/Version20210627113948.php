<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210627113948 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resource ADD storypoint_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416E79CBF5D FOREIGN KEY (storypoint_id) REFERENCES storypoint (id)');
        $this->addSql('CREATE INDEX IDX_BC91F416E79CBF5D ON resource (storypoint_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resource DROP FOREIGN KEY FK_BC91F416E79CBF5D');
        $this->addSql('DROP INDEX IDX_BC91F416E79CBF5D ON resource');
        $this->addSql('ALTER TABLE resource DROP storypoint_id');
    }
}
