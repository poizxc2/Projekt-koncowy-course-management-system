<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240120181814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_846B432D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_participant (id INT AUTO_INCREMENT NOT NULL, db_info_id INT DEFAULT NULL, user_id INT DEFAULT NULL, course_group_id INT DEFAULT NULL, grade DOUBLE PRECISION DEFAULT NULL, tech_stack VARCHAR(255) DEFAULT NULL, professor_note VARCHAR(255) DEFAULT NULL, INDEX IDX_A3F15BC55F29AB2 (db_info_id), UNIQUE INDEX UNIQ_A3F15BC5A76ED395 (user_id), INDEX IDX_A3F15BC557E0B411 (course_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE db_info (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_F727AF881D775834 (value), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(150) NOT NULL, role JSON NOT NULL COMMENT \'(DC2Type:json)\', student_number VARCHAR(15) DEFAULT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_participant ADD CONSTRAINT FK_A3F15BC55F29AB2 FOREIGN KEY (db_info_id) REFERENCES db_info (id)');
        $this->addSql('ALTER TABLE course_participant ADD CONSTRAINT FK_A3F15BC5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE course_participant ADD CONSTRAINT FK_A3F15BC557E0B411 FOREIGN KEY (course_group_id) REFERENCES course_group (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_participant DROP FOREIGN KEY FK_A3F15BC55F29AB2');
        $this->addSql('ALTER TABLE course_participant DROP FOREIGN KEY FK_A3F15BC5A76ED395');
        $this->addSql('ALTER TABLE course_participant DROP FOREIGN KEY FK_A3F15BC557E0B411');
        $this->addSql('DROP TABLE course_group');
        $this->addSql('DROP TABLE course_participant');
        $this->addSql('DROP TABLE db_info');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE users');
    }
}
