<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228212451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation CHANGE nom nom VARCHAR(500) NOT NULL, CHANGE email email VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD relation_reclamation_id INT NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC78A79131D FOREIGN KEY (relation_reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5FB6DEC78A79131D ON reponse (relation_reclamation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation CHANGE nom nom VARCHAR(500) DEFAULT NULL, CHANGE email email VARCHAR(60) DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC78A79131D');
        $this->addSql('DROP INDEX UNIQ_5FB6DEC78A79131D ON reponse');
        $this->addSql('ALTER TABLE reponse DROP relation_reclamation_id');
    }
}
