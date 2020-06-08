<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200606135005 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA872F68B530');
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA87AC870AE5');
        $this->addSql('DROP INDEX IDX_C6B7DA872F68B530 ON translations');
        $this->addSql('DROP INDEX IDX_C6B7DA87AC870AE5 ON translations');
        $this->addSql('ALTER TABLE translations ADD group_id INT NOT NULL, ADD lang_id INT NOT NULL, DROP group_id_id, DROP lang_id_id');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA87FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA87B213FA4 FOREIGN KEY (lang_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_C6B7DA87FE54D947 ON translations (group_id)');
        $this->addSql('CREATE INDEX IDX_C6B7DA87B213FA4 ON translations (lang_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA87FE54D947');
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA87B213FA4');
        $this->addSql('DROP INDEX IDX_C6B7DA87FE54D947 ON translations');
        $this->addSql('DROP INDEX IDX_C6B7DA87B213FA4 ON translations');
        $this->addSql('ALTER TABLE translations ADD group_id_id INT NOT NULL, ADD lang_id_id INT NOT NULL, DROP group_id, DROP lang_id');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA872F68B530 FOREIGN KEY (group_id_id) REFERENCES `group` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA87AC870AE5 FOREIGN KEY (lang_id_id) REFERENCES language (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C6B7DA872F68B530 ON translations (group_id_id)');
        $this->addSql('CREATE INDEX IDX_C6B7DA87AC870AE5 ON translations (lang_id_id)');
    }
}
