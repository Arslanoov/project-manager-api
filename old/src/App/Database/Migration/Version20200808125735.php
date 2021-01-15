<?php

declare(strict_types=1);

namespace App\Database\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 * @psalm-suppress DeprecatedClass
 */
final class Version20200808125735 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE todo_persons (id UUID NOT NULL, login VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_77550DA2AA08CB10 ON todo_persons (login)');
        $this->addSql('COMMENT ON COLUMN todo_persons.id IS \'(DC2Type:todo_person_id)\'');
        $this->addSql('COMMENT ON COLUMN todo_persons.login IS \'(DC2Type:todo_person_login)\'');
        $this->addSql('CREATE TABLE todo_schedules (id UUID NOT NULL, person_id UUID NOT NULL, date DATE NOT NULL, type VARCHAR(255) NOT NULL, tasksCount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CAE6A9E217BBB47 ON todo_schedules (person_id)');
        $this->addSql('COMMENT ON COLUMN todo_schedules.id IS \'(DC2Type:todo_schedule_id)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedules.person_id IS \'(DC2Type:todo_person_id)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedules.date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedules.type IS \'(DC2Type:todo_schedule_type)\'');
        $this->addSql('CREATE TABLE todo_schedule_task_steps (id int NOT NULL, task_id UUID NOT NULL, name VARCHAR(255) NOT NULL, sortOrder INT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FD20F0C38DB60186 ON todo_schedule_task_steps (task_id)');
        $this->addSql('COMMENT ON COLUMN todo_schedule_task_steps.id IS \'(DC2Type:todo_schedule_task_step_id)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedule_task_steps.task_id IS \'(DC2Type:todo_schedule_task_id)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedule_task_steps.name IS \'(DC2Type:todo_schedule_task_step_name)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedule_task_steps.sortOrder IS \'(DC2Type:todo_schedule_task_step_sort_order)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedule_task_steps.status IS \'(DC2Type:todo_schedule_task_step_status)\'');
        $this->addSql('CREATE TABLE todo_schedule_tasks (id UUID NOT NULL, schedule_id UUID NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, level VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4AF7685CA40BC2D5 ON todo_schedule_tasks (schedule_id)');
        $this->addSql('COMMENT ON COLUMN todo_schedule_tasks.id IS \'(DC2Type:todo_schedule_task_id)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedule_tasks.schedule_id IS \'(DC2Type:todo_schedule_id)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedule_tasks.name IS \'(DC2Type:todo_schedule_task_name)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedule_tasks.description IS \'(DC2Type:todo_schedule_task_description)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedule_tasks.level IS \'(DC2Type:todo_schedule_task_important_level)\'');
        $this->addSql('COMMENT ON COLUMN todo_schedule_tasks.status IS \'(DC2Type:todo_schedule_task_status)\'');
        $this->addSql('ALTER TABLE todo_schedules ADD CONSTRAINT FK_7CAE6A9E217BBB47 FOREIGN KEY (person_id) REFERENCES todo_persons (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE todo_schedule_task_steps ADD CONSTRAINT FK_FD20F0C38DB60186 FOREIGN KEY (task_id) REFERENCES todo_schedule_tasks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE todo_schedule_tasks ADD CONSTRAINT FK_4AF7685CA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES todo_schedules (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth_access_tokens ALTER client TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE oauth_access_tokens ALTER client DROP DEFAULT');
        $this->addSql('ALTER TABLE oauth_access_tokens ALTER scopes TYPE JSON');
        $this->addSql('ALTER TABLE oauth_access_tokens ALTER scopes DROP DEFAULT');
        $this->addSql('ALTER TABLE oauth_auth_codes ALTER client TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE oauth_auth_codes ALTER client DROP DEFAULT');
        $this->addSql('ALTER TABLE oauth_auth_codes ALTER scopes TYPE JSON');
        $this->addSql('ALTER TABLE oauth_auth_codes ALTER scopes DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE todo_schedules DROP CONSTRAINT FK_7CAE6A9E217BBB47');
        $this->addSql('ALTER TABLE todo_schedule_tasks DROP CONSTRAINT FK_4AF7685CA40BC2D5');
        $this->addSql('ALTER TABLE todo_schedule_task_steps DROP CONSTRAINT FK_FD20F0C38DB60186');
        $this->addSql('DROP TABLE todo_persons');
        $this->addSql('DROP TABLE todo_schedules');
        $this->addSql('DROP TABLE todo_schedule_task_steps');
        $this->addSql('DROP TABLE todo_schedule_tasks');
        $this->addSql('ALTER TABLE oauth_auth_codes ALTER client TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE oauth_auth_codes ALTER client DROP DEFAULT');
        $this->addSql('ALTER TABLE oauth_auth_codes ALTER scopes TYPE JSON');
        $this->addSql('ALTER TABLE oauth_auth_codes ALTER scopes DROP DEFAULT');
        $this->addSql('ALTER TABLE oauth_access_tokens ALTER client TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE oauth_access_tokens ALTER client DROP DEFAULT');
        $this->addSql('ALTER TABLE oauth_access_tokens ALTER scopes TYPE JSON');
        $this->addSql('ALTER TABLE oauth_access_tokens ALTER scopes DROP DEFAULT');
    }
}
