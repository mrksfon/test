<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220711073611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED99D86650F');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED99FCD471');
        $this->addSql('DROP INDEX IDX_68C58ED99D86650F ON favorite');
        $this->addSql('DROP INDEX IDX_68C58ED99FCD471 ON favorite');
        $this->addSql('ALTER TABLE favorite ADD user_id INT NOT NULL, ADD album_id INT NOT NULL, DROP user_id_id, DROP album_id_id');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED91137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('CREATE INDEX IDX_68C58ED9A76ED395 ON favorite (user_id)');
        $this->addSql('CREATE INDEX IDX_68C58ED91137ABCF ON favorite (album_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED91137ABCF');
        $this->addSql('DROP INDEX IDX_68C58ED9A76ED395 ON favorite');
        $this->addSql('DROP INDEX IDX_68C58ED91137ABCF ON favorite');
        $this->addSql('ALTER TABLE favorite ADD user_id_id INT NOT NULL, ADD album_id_id INT NOT NULL, DROP user_id, DROP album_id');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED99D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED99FCD471 FOREIGN KEY (album_id_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_68C58ED99D86650F ON favorite (user_id_id)');
        $this->addSql('CREATE INDEX IDX_68C58ED99FCD471 ON favorite (album_id_id)');
    }
}
