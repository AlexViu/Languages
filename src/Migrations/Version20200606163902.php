<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200606163902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456F2F68B530');
        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456FAC870AE5');
        $this->addSql('DROP INDEX IDX_B469456F2F68B530 ON translation');
        $this->addSql('DROP INDEX IDX_B469456FAC870AE5 ON translation');
        $this->addSql('ALTER TABLE translation ADD groups_id INT NOT NULL, ADD langs_id INT NOT NULL, DROP group_id_id, DROP lang_id_id');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456FF373DCF FOREIGN KEY (groups_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456F36C1FBEB FOREIGN KEY (langs_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_B469456FF373DCF ON translation (groups_id)');
        $this->addSql('CREATE INDEX IDX_B469456F36C1FBEB ON translation (langs_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456FF373DCF');
        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456F36C1FBEB');
        $this->addSql('DROP INDEX IDX_B469456FF373DCF ON translation');
        $this->addSql('DROP INDEX IDX_B469456F36C1FBEB ON translation');
        $this->addSql('ALTER TABLE translation ADD group_id_id INT NOT NULL, ADD lang_id_id INT NOT NULL, DROP groups_id, DROP langs_id');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456F2F68B530 FOREIGN KEY (group_id_id) REFERENCES `group` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456FAC870AE5 FOREIGN KEY (lang_id_id) REFERENCES language (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B469456F2F68B530 ON translation (group_id_id)');
        $this->addSql('CREATE INDEX IDX_B469456FAC870AE5 ON translation (lang_id_id)');
    }
}
