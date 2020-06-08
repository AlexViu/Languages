<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200606163701 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE translation (id INT AUTO_INCREMENT NOT NULL, group_id_id INT NOT NULL, lang_id_id INT NOT NULL, trans_key VARCHAR(45) NOT NULL, value VARCHAR(45) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, INDEX IDX_B469456F2F68B530 (group_id_id), INDEX IDX_B469456FAC870AE5 (lang_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456F2F68B530 FOREIGN KEY (group_id_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456FAC870AE5 FOREIGN KEY (lang_id_id) REFERENCES language (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE translation');
    }
}
