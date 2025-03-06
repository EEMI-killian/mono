<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250304234829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "like" (id SERIAL NOT NULL, user_id_id INT DEFAULT NULL, outfit_id INT DEFAULT NULL, item_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC6340B39D86650F ON "like" (user_id_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3AE96E385 ON "like" (outfit_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3126F525E ON "like" (item_id)');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B39D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3AE96E385 FOREIGN KEY (outfit_id) REFERENCES outfit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item ALTER is_public DROP DEFAULT');
        $this->addSql('ALTER TABLE outfit ALTER is_public DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B39D86650F');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3AE96E385');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3126F525E');
        $this->addSql('DROP TABLE "like"');
        $this->addSql('ALTER TABLE outfit ALTER is_public SET DEFAULT false');
        $this->addSql('ALTER TABLE item ALTER is_public SET DEFAULT false');
    }
}
