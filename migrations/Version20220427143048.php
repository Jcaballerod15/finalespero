<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427143048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mensaje (id INT AUTO_INCREMENT NOT NULL, idsala_id INT DEFAULT NULL, idemisor_id INT DEFAULT NULL, texto VARCHAR(200) NOT NULL, fechaenvio DATETIME NOT NULL, leido INT NOT NULL, INDEX IDX_9B631D011A188D51 (idsala_id), INDEX IDX_9B631D0115C42E2A (idemisor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sala (id INT AUTO_INCREMENT NOT NULL, idpersona1_id INT DEFAULT NULL, idpersona2_id INT DEFAULT NULL, INDEX IDX_E226041CA2E5AE99 (idpersona1_id), INDEX IDX_E226041CB0500177 (idpersona2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D011A188D51 FOREIGN KEY (idsala_id) REFERENCES sala (id)');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D0115C42E2A FOREIGN KEY (idemisor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sala ADD CONSTRAINT FK_E226041CA2E5AE99 FOREIGN KEY (idpersona1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sala ADD CONSTRAINT FK_E226041CB0500177 FOREIGN KEY (idpersona2_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D011A188D51');
        $this->addSql('DROP TABLE mensaje');
        $this->addSql('DROP TABLE sala');
    }
}
