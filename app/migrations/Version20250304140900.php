<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250304140900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('ALTER TABLE item ADD user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1F1B251E9D86650F ON item (user_id_id)');
        $this->addSql('ALTER TABLE outfit ADD user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE outfit ADD CONSTRAINT FK_320296019D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_320296019D86650F ON outfit (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251E9D86650F');
        $this->addSql('ALTER TABLE outfit DROP CONSTRAINT FK_320296019D86650F');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP INDEX IDX_1F1B251E9D86650F');
        $this->addSql('ALTER TABLE item DROP user_id_id');
        $this->addSql('DROP INDEX IDX_320296019D86650F');
        $this->addSql('ALTER TABLE outfit DROP user_id_id');
    }
}
