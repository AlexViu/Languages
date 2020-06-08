<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200608142715 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456FF373DCF');
        $this->addSql('CREATE TABLE `container` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456F36C1FBEB');
        $this->addSql('DROP INDEX IDX_B469456F36C1FBEB ON translation');
        $this->addSql('DROP INDEX IDX_B469456FF373DCF ON translation');
        $this->addSql('ALTER TABLE translation ADD container_id INT NOT NULL, ADD lang_id INT NOT NULL, DROP groups_id, DROP langs_id');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456FBC21F742 FOREIGN KEY (container_id) REFERENCES `container` (id)');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456FB213FA4 FOREIGN KEY (lang_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_B469456FBC21F742 ON translation (container_id)');
        $this->addSql('CREATE INDEX IDX_B469456FB213FA4 ON translation (lang_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456FBC21F742');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE `container`');
        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456FB213FA4');
        $this->addSql('DROP INDEX IDX_B469456FBC21F742 ON translation');
        $this->addSql('DROP INDEX IDX_B469456FB213FA4 ON translation');
        $this->addSql('ALTER TABLE translation ADD groups_id INT NOT NULL, ADD langs_id INT NOT NULL, DROP container_id, DROP lang_id');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456F36C1FBEB FOREIGN KEY (langs_id) REFERENCES language (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456FF373DCF FOREIGN KEY (groups_id) REFERENCES `group` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B469456F36C1FBEB ON translation (langs_id)');
        $this->addSql('CREATE INDEX IDX_B469456FF373DCF ON translation (groups_id)');
    }
}
