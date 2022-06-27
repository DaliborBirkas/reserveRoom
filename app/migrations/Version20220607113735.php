<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607113735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE room_time');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE room_time (room_id INT NOT NULL, time_id INT NOT NULL, INDEX IDX_C90722154177093 (room_id), INDEX IDX_C9072215EEADD3B (time_id), PRIMARY KEY(room_id, time_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE room_time ADD CONSTRAINT FK_C90722154177093 FOREIGN KEY (room_id) REFERENCES room (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_time ADD CONSTRAINT FK_C9072215EEADD3B FOREIGN KEY (time_id) REFERENCES time (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
