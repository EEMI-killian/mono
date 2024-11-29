<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129110018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD is_phone_number_verified BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD is_email_verified BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD role VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP is_admin');
        $this->addSql('ALTER TABLE "user" DROP is_verfied');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE challenge');
        $this->addSql('ALTER TABLE "user" ADD is_admin BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD is_verfied BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP is_phone_number_verified');
        $this->addSql('ALTER TABLE "user" DROP is_email_verified');
        $this->addSql('ALTER TABLE "user" DROP role');
    }
}
