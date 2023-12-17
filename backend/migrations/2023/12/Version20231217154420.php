<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217154420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавил поле host в таблицу user';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auth."user" ADD host VARCHAR(100) DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN auth."user".host IS \'Хост-источник пользователя\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "auth"."user" DROP host');
    }
}
