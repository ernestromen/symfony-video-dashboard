<?php
// migrations/Version20241122141912.php
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20241122141912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create UserRole and VideoRole tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_role (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) NOT NULL, role_id INT NOT NULL, INDEX IDX_B2C0A18F1C8C07D5 (user_id), INDEX IDX_B2C0A18F8D93D649 (role_id), created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE video_role (id INT AUTO_INCREMENT NOT NULL, video_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_5B51059F9F1C0C1B (video_id), INDEX IDX_5B51059F8D93D649 (role_id), created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE video_role');
        $this->addSql('DROP TABLE roles');
    }
}
