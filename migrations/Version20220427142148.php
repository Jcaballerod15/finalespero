<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427142148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clase (id INT AUTO_INCREMENT NOT NULL, idprofe_id INT DEFAULT NULL, descripclase VARCHAR(200) NOT NULL, titulo VARCHAR(100) NOT NULL, claveprivada VARCHAR(200) DEFAULT NULL, INDEX IDX_199FACCE6278E387 (idprofe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenido (id INT AUTO_INCREMENT NOT NULL, idclase_id INT DEFAULT NULL, fechapublica DATETIME NOT NULL, texto VARCHAR(150) DEFAULT NULL, imagen LONGBLOB DEFAULT NULL, video VARCHAR(255) DEFAULT NULL, INDEX IDX_D0A7397F35A74B5E (idclase_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nombre VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_clase (user_id INT NOT NULL, clase_id INT NOT NULL, INDEX IDX_FB5D5796A76ED395 (user_id), INDEX IDX_FB5D57969F720353 (clase_id), PRIMARY KEY(user_id, clase_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clase ADD CONSTRAINT FK_199FACCE6278E387 FOREIGN KEY (idprofe_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contenido ADD CONSTRAINT FK_D0A7397F35A74B5E FOREIGN KEY (idclase_id) REFERENCES clase (id)');
        $this->addSql('ALTER TABLE user_clase ADD CONSTRAINT FK_FB5D5796A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_clase ADD CONSTRAINT FK_FB5D57969F720353 FOREIGN KEY (clase_id) REFERENCES clase (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenido DROP FOREIGN KEY FK_D0A7397F35A74B5E');
        $this->addSql('ALTER TABLE user_clase DROP FOREIGN KEY FK_FB5D57969F720353');
        $this->addSql('ALTER TABLE clase DROP FOREIGN KEY FK_199FACCE6278E387');
        $this->addSql('ALTER TABLE user_clase DROP FOREIGN KEY FK_FB5D5796A76ED395');
        $this->addSql('DROP TABLE clase');
        $this->addSql('DROP TABLE contenido');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_clase');
    }
}
