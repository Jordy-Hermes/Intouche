<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221213211634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE add_fields CHANGE id_contact contact_id INT NOT NULL');
        $this->addSql('ALTER TABLE add_fields ADD CONSTRAINT FK_923BA2A4E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('CREATE INDEX IDX_923BA2A4E7A1254A ON add_fields (contact_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE add_fields DROP FOREIGN KEY FK_923BA2A4E7A1254A');
        $this->addSql('DROP INDEX IDX_923BA2A4E7A1254A ON add_fields');
        $this->addSql('ALTER TABLE add_fields CHANGE contact_id id_contact INT NOT NULL');
    }
}
